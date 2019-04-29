<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ddeboer\Imap\Server;
use App\User;
use App\Group;
use App\Discussion;
use Log;
use EmailReplyParser\Parser\EmailParser;

class CheckMailbox extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'agorakit:checkmailbox
  {{--keep : Use this to keep a copy of the email in the mailbox. Only for development!}}
  {{--debug : Print each email content. Only for development!}}
  ';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Check the configured email imap server to allow post by email functionality';

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
        if (setting('user_can_post_by_email')) {
            // open mailbox

            $server = new Server(setting('mail_server'));

            // $connection is instance of \Ddeboer\Imap\Connection
            $connection = $server->authenticate(setting('mail_login'), setting('mail_password'));

            $mailboxes = $connection->getMailboxes();

            foreach ($mailboxes as $mailbox) {
                // Skip container-only mailboxes
                // @see https://secure.php.net/manual/en/function.imap-getmailboxes.php
                if ($mailbox->getAttributes() & \LATT_NOSELECT) {
                    continue;
                }

                // $mailbox is instance of \Ddeboer\Imap\Mailbox
                $this->line('Mailbox ' . $mailbox->getName() . ' has ' . $mailbox->count() . ' messages');
            }

            // open INBOX

            $inbox = $connection->getMailbox('INBOX');

            if ($inbox->count() == 0) {
                $this->info('No new emails in inbox');
            }

            // Open/create processed mailboxes
            if ($connection->hasMailbox('processed')) {
                $processed_mailbox = $connection->getMailbox('processed');
            } else {
                $processed_mailbox = $connection->createMailbox('processed');
            }

            // Open/create failed mailboxes
            if ($connection->hasMailbox('failed')) {
                $failed_mailbox = $connection->getMailbox('failed');
            } else {
                $failed_mailbox = $connection->createMailbox('failed');
            }

            $messages = $inbox->getMessages();

            foreach ($messages as $message) {
                if ($this->option('debug')) {
                    print_r($message);
                }

                $success = false;

                $this->info('Got a message from ' . $message->getFrom()->getAddress());

                // check that is is sent from an existing user
                $user = User::where('email', $message->getFrom()->getAddress())->first();
                if ($user) {
                    $this->info('This user exists, full name is ' . $user->name . ' / id is : ' . ($user->id));

                    // check that each mail to: is an existing group
                    foreach ($message->getTo() as $to) {
                        $search = $to->getAddress();
                        // remove prefix and suffix to get the slug we need to check against
                        $search = str_replace(setting('mail_prefix'), '', $search);
                        $search = str_replace(setting('mail_suffix'), '', $search);



                        // check that is is sent to an existing group
                        $group = Group::where('slug', $search)->first();
                        if ($group) {
                            $this->info('Group ' . $group->name . ' exists');

                            // check that the user is a member of the group
                            if ($user->isMemberOf($group)) {
                                $this->info($user->name . ' is a member of this group');

                                $discussion = new Discussion;

                                $discussion->name = $message->getSubject();


                                $body_html = $message->getBodyHtml(); // this is the raw html content
                                $body_text = nl2br(\EmailReplyParser\EmailReplyParser::parseReply($message->getBodyText()));

                                // count the number of lines in plain text :
                                // if we really have nothing in there using plain text, let's post the whole html mess from the email
                                if (count(explode("\n", $body_text)) < 2) {
                                    $discussion->body = $body_html;
                                } else {
                                    $discussion->body = $body_text;
                                }

                                $discussion->total_comments = 1; // the discussion itself is already a comment
                                $discussion->user()->associate($user);

                                if ($this->option('debug')) {
                                    print_r($discussion->body);
                                }



                                if ($group->discussions()->save($discussion)) {
                                    // update activity timestamp on parent items
                                    $group->touch();
                                    $user->touch();
                                    $this->info('Discussion has been created with id : ' . $discussion->id);
                                    $this->info('Title : ' . $discussion->name);
                                    Log::info('Discussion has been created from email', ['mail'=> $message, 'discussion' => $discussion]);

                                    $success = true;
                                } else {
                                    $this->error('Could not create discussion');
                                    Log::error('Could not create discussion', ['mail'=> $mail, 'discussion' => $discussion]);
                                }
                            } else {
                                $this->error($user->name . ' is not a member of ' . $group->name);
                            }
                        } else {
                            $this->error('No group named ' . $search);
                        }
                    }
                } else {
                    $this->error('No user found with ' . $message->getFrom()->getAddress());
                }

                // move message to the correct mailbox depending on outcome
                if ($success) {
                    $message->move($processed_mailbox);
                    $this->info('Email has been moved to the processed folder on the mail server');
                    Log::info('Email has been moved to the processed folder on the mail server', ['mail_id'=> $message->getId()]);
                } else {
                    $message->move($failed_mailbox);
                    $this->info('Email has been moved to the failed folder on the mail server');
                    Log::info('Email has been moved to the failed folder on the mail server', ['mail_id'=> $message->getId()]);
                }
            }

            $this->line('-----------------------------------');

            $connection->expunge();
        } else {
            $this->info('Email checking disabled in settings');
            return false;
        }
    }
}
