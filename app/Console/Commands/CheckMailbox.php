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
use League\HTMLToMarkdown\HtmlConverter;
use Michelf\Markdown;
use EmailReplyParser\EmailReplyParser;

/*
Inbound  Email handler for Agorakit

High level overview :

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

Emails are generated as follow :  

[INBOX_PREFIX][group-slug][INBOX_SUFFIX]

[INBOX_PREFIX]reply-[discussion-id][INBOX_SUFFIX]

Prefix and suffix is defined in the .env file

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
    {{--debug : Show debug info.}}
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

        if (config('agorakit.inbox_host')) {
            // open mailbox

            $this->server = new Server(config('agorakit.inbox_host'), config('agorakit.inbox_port'), config('agorakit.inbox_flags'));

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
                    $this->line('Mailbox ' . $mailbox->getName() . ' has ' . $mailbox->count() . ' messages');
                }
            }

            // open INBOX
            $this->inbox = $this->connection->getMailbox('INBOX');


            // get all messages
            $messages = $this->inbox->getMessages();


            $i = 0;
            foreach ($messages as $message) {

                $this->line('-----------------------------------');

                // limit to 100 messages at a time (I did no find a better way to handle this iterator)
                if ($i > 100) {
                    break;
                }
                $i++;


                // debug message info
                if ($this->debug) {
                    $this->line('Processing email "' . $message->getSubject() . '"');
                    $this->line('From ' . $message->getFrom()->getAddress());
                }

                // discard automated messages
                if ($this->isMessageAutomated($message)) {
                    $this->moveMessage($message, 'automated');
                    $this->line('Message discarded because automated');
                    continue;
                }


                // Try to find a $user, $group and $discussion from the $message
                $user = $this->extractUserFromMessage($message);
                $group = $this->extractGroupFromMessage($message);
                $discussion = $this->extractDiscussionFromMessage($message);

                // Decide what to do
                if ($discussion && $user->exists && $user->isMemberOf($discussion->group)) {
                    $this->info('Discussion exists and user is member of group, posting message');
                    $this->processDiscussionExistsAndUserIsMember($discussion, $user, $message);
                } elseif ($group && $user->exists && $user->isMemberOf($group)) {
                    $this->info('User exists and is member of group, posting message');
                    $this->processGroupExistsAndUserIsMember($group, $user, $message);
                } elseif ($group && $user->exists && !$user->isMemberOf($group)) {
                    $this->info('User exists BUT is not member of group, bouncing and inviting');
                    $this->processGroupExistsButUserIsNotMember($group, $user, $message);
                } else {
                    if (!$user->exists) {
                        $this->moveMessage($message, 'user_not_found');
                    } elseif (!$group) {
                        $this->moveMessage($message, 'group_not_found');
                    } elseif (!$discussion) {
                        $this->moveMessage($message, 'discussion_not_found');
                    }
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
    public function debug($message)
    {
        if ($this->option('debug')) {
            $this->line($message);
        }
    }


    /**
     * Tries to find a valid user in the $message (using from: email header)
     * Else returns a new user with the email already set
     */
    public function extractUserFromMessage(Message $message)
    {
        $user = User::where('email', $message->getFrom()->getAddress())->firstOrNew();
        if (!$user->exists) {
            $this->debug('User does not exist, created from: '  . $user->email);
            $user->email = $message->getFrom()->getAddress();
        } else {
            $this->debug('User exists, from: '  . $user->email);
        }

        return $user;
    }

    // tries to find a valid group in the $message (using to: email header)
    public function extractGroupFromMessage(Message $message)
    {
        $recipients = $this->extractRecipientsFromMessage($message);
        
        foreach ($recipients as $to_email) {
            // remove prefix and suffix to get the slug we need to check against
            $to_email = str_replace(config('agorakit.inbox_prefix'), '', $to_email);
            $to_email = str_replace(config('agorakit.inbox_suffix'), '', $to_email);

            $to_emails[] = $to_email;

            $this->debug('to: '  . $to_email);
        }

        $group = Group::whereIn('slug', $to_emails)->first();


        if ($group) {
            $this->debug('group found');
            return $group;
        }
        $this->debug('group not found');
        return false;
    }


    // tries to find a valid discussion in the $message (using to: email header and message content)
    public function extractDiscussionFromMessage(Message $message)
    {
        $recipients = $this->extractRecipientsFromMessage($message);
        
        foreach ($recipients as $to_email) {
            preg_match('#' . config('agorakit.inbox_prefix') . 'reply-(\d+)' . config('agorakit.inbox_prefix') . '#', $to_email, $matches);
            //dd($matches);
            if (isset($matches[1])) {
                $discussion = Discussion::where('id', $matches[1])->first();


                if ($discussion) {
                    $this->debug('discussion found');
                    return $discussion;
                }
            }
        }

        $this->debug('discussion not found');
        return false;
    }

    /**
     * Returns a rich text represenation of the email, stripping away all quoted text, signatures, etc...
     */
    function extractTextFromMessage(Message $message)
    {
        $body_html = $message->getBodyHtml(); // this is the raw html content
        $body_text = nl2br(EmailReplyParser::parseReply($message->getBodyText()));


        // count the number of caracters in plain text :
        // if we really have less than 5 chars in there using plain text,
        // let's post the whole html mess, 
        // converted to markdown, 
        // then stripped with the same EmailReplyParser, 
        // then converted from markdown back to html, pfeeew
        if (strlen($body_text) < 5) {
            $converter = new HtmlConverter();
            $markdown = $converter->convert($body_html);
            $result = Markdown::defaultTransform(EmailReplyParser::parseReply($markdown));
        } else {
            $result = $body_text;
        }

        return $result;
    }

    /** 
     * Returns all recipients form the message, in the to: and cc: fields
     */
    function extratRecipientsFromMessage(Message $message)
    {
        $recipients = [];
        
        foreach ($message->getTo() as $to) {
            $recipients[] = $to->getAddress();
        }

        foreach ($message->getCc() as $to) {
            $recipients[] = $to->getAddress();
        }

        return $recipients;

    }

    function parse_rfc822_headers(string $header_string): array
    {
        // Reference:
        // * Base: https://stackoverflow.com/questions/5631086/getting-x-mailer-attribute-in-php-imap/5631445#5631445
        // * Improved regex: https://stackoverflow.com/questions/5631086/getting-x-mailer-attribute-in-php-imap#comment61912182_5631445
        preg_match_all(
            '/([^:\s]+): (.*?(?:\r\n\s(?:.+?))*)\r\n/m',
            $header_string,
            $matches
        );
        $headers = array_combine($matches[1], $matches[2]);
        return $headers;
    }




    /**
     * Returns true if message is an autoreply or vacation auto responder
     */
    public function isMessageAutomated(Message $message)
    {
        /*
        TODO Detect automatic messages and discard them, see here : https://www.arp242.net/autoreply.html
        */

        $message_headers = $this->parse_rfc822_headers($message->getRawHeaders());

        if (array_key_exists('Auto-Submitted', $message_headers)) {
            return true;
        }

        if (array_key_exists('X-Auto-Response-Suppress', $message_headers)) {
            return true;
        }

        if (array_key_exists('List-Id', $message_headers)) {
            return true;
        }

        if (array_key_exists('List-Unsubscribe', $message_headers)) {
            return true;
        }

        if (array_key_exists('Feedback-ID', $message_headers)) {
            return true;
        }

        if (array_key_exists('X-NIDENT', $message_headers)) {
            return true;
        }

        if (array_key_exists('Delivered-To', $message_headers)) {
            if ($message_headers['Delivered-To'] == 'Autoresponder') {
                return true;
            }
        }

        if (array_key_exists('X-AG-AUTOREPLY', $message_headers)) {
            return true;
        }



        return false;
    }

    /**
     * Move the provided $message to a folder named $folder
     */
    public function moveMessage(Message $message, $folder)
    {
        // don't move message in dev
        if ($this->option('keep')) {
            return true;
        }

        if ($this->connection->hasMailbox($folder)) {
            $folder = $this->connection->getMailbox($folder);
        } else {
            $folder = $this->connection->createMailbox($folder);
        }

        return $message->move($folder);
    }




    public function processGroupExistsAndUserIsMember(Group $group, User $user, Message $message)
    {
        $discussion = new Discussion();

        $discussion->name = $message->getSubject();
        $discussion->body = $this->extractTextFromMessage($message);

        $discussion->total_comments = 1; // the discussion itself is already a comment
        $discussion->user()->associate($user);

        if ($group->discussions()->save($discussion)) {
            // update activity timestamp on parent items
            $group->touch();
            $user->touch();
            $this->info('Discussion has been created with id : ' . $discussion->id);
            $this->info('Title : ' . $discussion->name);
            Log::info('Discussion has been created from email', ['mail' => $message, 'discussion' => $discussion]);

            $this->moveMessage($message, 'processed');
            return true;
        } else {
            $this->moveMessage($message, 'discussion_not_created');
            Mail::to($user)->send(new MailBounce($message, 'Your discussion could not be created in the group, maybe your message was empty'));
            return false;
        }
    }


    public function processDiscussionExistsAndUserIsMember(Discussion $discussion, User $user, Message $message)
    {
        $discussion->body = $this->extractTextFromMessage($message);

        $comment = new \App\Comment();
        $comment->body = $body;
        $comment->user()->associate($user);
        if ($discussion->comments()->save($comment)) {
            $discussion->total_comments++;
            $discussion->save();

            // update activity timestamp on parent items
            $discussion->group->touch();
            $discussion->touch();
            $this->moveMessage($message, 'processed');
            return true;
        } else {
            $this->moveMessage($message, 'comment_not_created');
            return false;
        }
    }


    public function processGroupExistsButUserIsNotMember(Group $group, User $user, Message $message)
    {
        Mail::to($user)->send(new MailBounce($message, 'You are not member of ' . $group->name . ' please join the group first before posting'));
        $this->moveMessage($message, 'bounced');
    }


    public function processGroupNotFoundUserNotFound(User $user, Message $message)
    {
        $this->moveMessage($message, 'group_not_found_user_not_found');
    }
}
