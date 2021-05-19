<?php

namespace App\Console\Commands;

use App\Message;
use App\Discussion;
use App\Group;
use App\User;
use Ddeboer\Imap\Server;
use Ddeboer\Imap\Message as ImapMessage;
use Illuminate\Console\Command;
use Log;
use Mail;
use App\Mail\MailBounce;
use League\HTMLToMarkdown\HtmlConverter;
use Michelf\Markdown;
use EmailReplyParser\EmailReplyParser;

/*
Inbound Email handler for Agorakit, allows user to post content by email, first step

Everytime it's run, emails found in the defined inbox are imported in the messages table

Read this issue for an overview : https://github.com/agorakit/agorakit/issues/371

A catch-all email is required, or an email server supporting "+" adressing.
This command simply imports all emails in the messages table and remove it from the mailserver

After that, the agorakit:processmessages command should be called, it's the second step of the process

TODO : create a POP3 mail parser as well, would be very easy to do

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
        if (config('agorakit.inbox_host')) {
            // open mailbox

            $this->server = new Server(config('agorakit.inbox_host'), config('agorakit.inbox_port'), config('agorakit.inbox_flags'));

            // $this->connection is instance of \Ddeboer\Imap\Connection
            $this->connection = $this->server->authenticate(config('agorakit.inbox_username'), config('agorakit.inbox_password'));

            $mailboxes = $this->connection->getMailboxes();

            if ($this->option('debug')) {
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
            foreach ($messages as $mailbox_message) {

                $this->debug('-----------------------------------');

                // limit to 100 messages at a time (I did no find a better way to handle this iterator)
                if ($i > 100) {
                    break;
                }
                $i++;

                $this->debug('Processing email "' . $mailbox_message->getSubject() . '"');
                $this->debug('From ' . $mailbox_message->getFrom()->getAddress());


                $message = new Message;

                $message->subject = $mailbox_message->getSubject();
                $message->from = $mailbox_message->getFrom()->getAddress();
                $message->raw = $mailbox_message->getRawMessage();

                $message->saveOrFail();

                $this->moveMessage($mailbox_message, 'stored');
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
     * Move the provided $message to a folder named $folder
     */
    public function moveMessage(ImapMessage $message, $folder)
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

}
