<?php

namespace App\Console\Commands;

use App\Discussion;
use App\Group;
use App\User;
use Ddeboer\Imap\Server;
use Ddeboer\Imap\Message;
use Illuminate\Console\Command;
use Log;
use Mail;
use App\Mail\MailBounce;

/*
Inbound  Email handler for Agorakit

Multiple cases :

1. Sending email to a group
- The user exists
- The group exists
- The user is a member of the group

2. Replying to a discussion
- discussion exists
- user is a member of the group

In all cases the mail is processed
Bounced to user in case of failure
Moved to a folder on the imap server

*/

class CheckMailbox extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'agorakit:checkmailbox
    {{--keep : Use this to keep a copy of the email in the mailbox. Only for development!}}
    {{--debug : Show debug info and does not move emails on imap server. Only for development!}}
    ';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Check the configured email imap server to allow post by email functionality';

    protected $debug = false;

    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }



    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        if ($this->option('debug')) {
            $this->debug = true;
        }

        if (setting('user_can_post_by_email')) {
            // open mailbox

            $this->server = new Server(config('agorakit.inbox_host'));

            // $this->connection is instance of \Ddeboer\Imap\Connection
            $this->connection = $this->server->authenticate(config('agorakit.inbox_username'), config('agorakit.inbox_password'));

            $mailboxes = $this->connection->getMailboxes();

            if ($this->debug) {
            foreach ($mailboxes as $mailbox) {
                // Skip container-only mailboxes
                // @see https://secure.php.net/manual/en/function.imap-getmailboxes.php
                if ($mailbox->getAttributes() & \LATT_NOSELECT) {
                    continue;
                }
                // $mailbox is instance of \Ddeboer\Imap\Mailbox
                $this->line('Mailbox '.$mailbox->getName().' has '.$mailbox->count().' messages');
            }
        }

            // open INBOX
            $this->inbox = $this->connection->getMailbox('INBOX');

            // create the required imap folders
            $this->createImapFolders();

            // get all messages
            $messages = $this->inbox->getMessages();


            $i = 0;
            foreach ($messages as $message) {

                $this->line('-----------------------------------');

                // limit to 50 messages at a time (I did no find a better way to handle this iterator)
                if ($i > 100) {
                    break;
                }
                $i++;


                if ($this->debug) {
                    $this->line('Processing email "' . $message->getSubject() . '"');
                    $this->line('From ' . $message->getFrom()->getAddress());
                }


                // Try to find a $user and a $group from the $message
                $user = $this->extractUserFromMessage($message);
                $group = $this->extractGroupFromMessage($message);

                // Decide what to do
                if ($group && $user->exists && $user->isMemberOf($group)){
                    $this->info('User exists and is member of group, posting message');
                    $this->processGroupExistsAndUserIsMember($group, $user, $message);
                }
                elseif ($group && $user->exists && !$user->isMemberOf($group)){
                    $this->info('User exists BUT is not member of group, bouncing and inviting');
                    $this->processGroupExistsButUserIsNotMember($group, $user, $message);
                }
                else {
                    $this->error('Invalid user and/or group, discarding email');
                    $this->processGroupNotFoundUserNotFound($user, $message);
                }

                // TODO handle the case of user exists but group doesn't -> might be a good idea to bounce back to user


            }
            $this->connection->expunge();
        } else {
            $this->info('Email checking disabled in settings');
            return false;
        }
    }



    /**
    * Small helper debug output
    */
    public function debug($message) {
        if ($this->option('debug')) {
            $this->line($message);
        }
    }


    // tries to find a valid user in the $message (using from: email header)
    public function extractUserFromMessage(Message $message)
    {
        $user = User::where('email', $message->getFrom()->getAddress())->firstOrNew();
        if (!$user->exists){
            $user->email = $message->getFrom()->getAddress();
        }
        $this->debug('from: header : '  . $user->email);
        return $user;
    }

    // tries to find a valid group in the $message (using to: email header)
    public function extractGroupFromMessage(Message $message)
    {
        $to_emails = [];
        foreach ($message->getTo() as $to) {
            $to_email = $to->getAddress();
            // remove prefix and suffix to get the slug we need to check against
            $to_email = str_replace(config('agorakit.inbox_prefix'), '', $to_email);
            $to_email = str_replace(config('agorakit.inbox_suffix'), '', $to_email);

            $to_emails[] = $to_email;

            $this->debug('to: header : '  . $to_email);
        }

        $group = Group::whereIn('slug', $to_emails)->first();


        if ($group) {
            $this->debug('group found');
            return $group;
        }
        $this->debug('group not found');
        return false;

    }

    public function createImapFolders()
    {
        // Open/create processed mailboxes
        if ($this->connection->hasMailbox('processed')) {
            $this->mailbox['processed'] = $this->connection->getMailbox('processed');
        } else {
            $this->mailbox['processed'] = $this->connection->createMailbox('processed');
        }

        // Open/create discarded mailboxes
        if ($this->connection->hasMailbox('discarded')) {
            $this->mailbox['discarded'] = $this->connection->getMailbox('discarded');
        } else {
            $this->mailbox['discarded'] = $this->connection->createMailbox('discarded');
        }

        // Open/create bounced mailboxes
        if ($this->connection->hasMailbox('bounced')) {
            $this->mailbox['bounced'] = $this->connection->getMailbox('bounced');
        } else {
            $this->mailbox['bounced'] = $this->connection->createMailbox('bounced');
        }

        // Open/create bounced mailboxes
        if ($this->connection->hasMailbox('failed')) {
            $this->mailbox['failed'] = $this->connection->getMailbox('failed');
        } else {
            $this->mailbox['failed'] = $this->connection->createMailbox('failed');
        }
    }

    public function processGroupExistsAndUserIsMember(Group $group, User $user, Message $message)
    {
        $discussion = new Discussion();

        $discussion->name = $message->getSubject();

        $body_html = $message->getBodyHtml(); // this is the raw html content
        $body_text = nl2br(\EmailReplyParser\EmailReplyParser::parseReply($message->getBodyText()));


        // count the number of caracters in plain text :
        // if we really have less than 5 chars in there using plain text,
        // let's post the whole html mess from the email
        if (strlen($body_text) < 5) {
            $discussion->body = $body_html;
        } else {
            $discussion->body = $body_text;
        }

        $discussion->total_comments = 1; // the discussion itself is already a comment
        $discussion->user()->associate($user);

        if ($group->discussions()->save($discussion)) {
            // update activity timestamp on parent items
            $group->touch();
            $user->touch();
            $this->info('Discussion has been created with id : '.$discussion->id);
            $this->info('Title : '.$discussion->name);
            Log::info('Discussion has been created from email', ['mail'=> $message, 'discussion' => $discussion]);

            $message->move($this->mailbox['processed']);
            return true;
        }
        else {
            $message->move($this->mailbox['failed']);
            return false;
        }
    }


    public function processGroupExistsButUserIsNotMember(Group $group, User $user, Message $message)
    {
        Mail::to($user)->send(new MailBounce($message, 'You are not member of ' . $group->name . ' please join the group first before posting'));
        $message->move($this->mailbox['bounced']);
    }


    public function processGroupNotFoundUserNotFound(User $user, Message $message)
    {
        $message->move($this->mailbox['discarded']);
    }


    /*
    TODO Detect automatic messages and discard them, see here : https://www.arp242.net/autoreply.html
    */



}





/*

if ($user->exists) {
$this->info('This user exists, full name is '.$user->name.' / id is : '.($user->id));

// check that each mail to: is an existing group
if ($group) {

// check that the user is a member of the group
if ($user->isMemberOf($group)) {
$this->info($user->name.' is a member of this group');

$discussion = new Discussion();

$discussion->name = $message->getSubject();

$body_html = $message->getBodyHtml(); // this is the raw html content
$body_text = nl2br(\EmailReplyParser\EmailReplyParser::parseReply($message->getBodyText()));

// count the number of caracters in plain text :
// if we really have less than 5 chars in there using plain text,
// let's post the whole html mess from the email
if (count($body_text) < 5) {
$discussion->body = $body_html;
} else {
$discussion->body = $body_text;
}

$discussion->total_comments = 1; // the discussion itself is already a comment
$discussion->user()->associate($user);

if ($this->debug) {
print_r($discussion->body);
}

if ($group->discussions()->save($discussion)) {
// update activity timestamp on parent items
$group->touch();
$user->touch();
$this->info('Discussion has been created with id : '.$discussion->id);
$this->info('Title : '.$discussion->name);
Log::info('Discussion has been created from email', ['mail'=> $message, 'discussion' => $discussion]);

$success = true;
} else {
$this->error('Could not create discussion');
Log::error('Could not create discussion', ['mail'=> $message, 'discussion' => $discussion]);
}
} else {
$this->error($user->name.' is not a member of '.$group->name);
// bounce mail
Mail::to($user)->send(new MailBounce($message, 'You are not a member of this group'));
}
} else {
$this->error('No group named '. $to_email);
// bounce mail
Mail::to($user)->send(new MailBounce($message, 'There is no group named ' . $to_email . ' at this adress'));
}
}
} else {
$this->error('No user found with '.$message->getFrom()->getAddress());
// bounce mail, but
// bouncing to a non member might create spam problems :
Mail::to($message->getFrom()->getAddress())->send(new MailBounce($message, 'You are not registered on this server, please create an account first'));
}

// move message to the correct mailbox depending on outcome
if ($success) {
if ($this->debug) {
$this->info('It worked but since debug is enabled the message has not been moved');
}
else
{
$message->move($processed_mailbox);
$this->info('Email has been moved to the processed folder on the mail server');
Log::info('Email has been moved to the processed folder on the mail server', ['mail_id'=> $message->getId()]);
}
} else {
if ($this->debug) {
$this->error('It did\'nt  work but since debug is enabled the message has not been moved');
}
else
{
$message->move($failed_mailbox);
$this->error('Email has been moved to the failed folder on the mail server');
Log::info('Email has been moved to the failed folder on the mail server', ['mail_id'=> $message->getId()]);
}
}
}
*/
