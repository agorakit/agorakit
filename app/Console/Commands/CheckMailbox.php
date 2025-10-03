<?php

namespace App\Console\Commands;

use App\Discussion;
use App\Comment;
use App\Group;
use App\User;
use Exception;
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
Inbound Email handler for Agorakit, allows user to post content by email

Everytime it's run, emails found in the defined inbox are processed

- find a sender in the existing users list or create a user
- find a recipient in the to and other mail header. It can a group or a discussion
- check the the user can post to this recipient
- process email content (filter) and create the appropriate content (discussion or commment)


A catch-all email is required, or an email server supporting "+" adressing.

Emails are generated as follow :

[INBOX_PREFIX][group-slug][INBOX_SUFFIX]

[INBOX_PREFIX]reply-[discussion-id][INBOX_SUFFIX]

Prefix and suffix is defined in the .env file as well as the server credentials

Only imap is supported since it allows to store all processed and failed emails in separate folders on the mail server for further inspection

*/

class CheckMailbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:checkmailbox
    {--dry}
    {--debug}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the configured email imap server to allow post by email functionality';

    protected $dry = true;
    protected $debug = true;

    protected $connection;
    protected $inbox;
    protected $server;

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
        if (!function_exists('imap_open')) {
                $this->info('IMAP extension must be enabled');
            return false;
        }

        if (!config('agorakit.inbox_host')) {
            $this->info('Email checking disabled in settings');
            return false;
        }

        $this->dry = $this->option('dry');

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
                $this->debug('Mailbox ' . $mailbox->getName() . ' has ' . $mailbox->count() . ' messages');
            }
        }

        // open INBOX
        $this->inbox = $this->connection->getMailbox('INBOX');

        // get all messages
        $messages = $this->inbox->getMessages();

        $i = 0;
        foreach ($messages as $message) {
            try {
                $this->debug('-----------------------------------');

                // limit to 100 messages at a time (I did no find a better way to handle this iterator)
                if ($i > 100) {
                    break;
                }

                $i++;


                $this->debug('Processing email "' . $message->getSubject() . '"');
                $this->debug('From ' . $message->getFrom()->getAddress());


                // discard automated messages
                if ($this->isMessageAutomated($message)) {
                    $this->moveMessage($message, 'automated');
                    $this->debug('Message discarded because automated');
                    continue;
                }


                // Try to find a $user, $group and $discussion from the $message
                $user = $this->extractUserFromMessage($message);
                $group = $this->extractGroupFromMessage($message);
                $discussion = $this->extractDiscussionFromMessage($message);

                // Decide what to do
                // TODO handle the case of user exists but group doesn't -> might be a good idea to bounce back to user
                if ($discussion && $user->exists && $user->isMemberOf($discussion->group)) {
                    $this->debug('Discussion exists and user is member of group, posting new comment to discussion');
                    $this->processDiscussionExistsAndUserIsMember($discussion, $user, $message);
                } elseif ($group && $user->exists && $user->isMemberOf($group)) {
                    $this->debug('User exists and is member of group, posting new discussion');
                    $this->processGroupExistsAndUserIsMember($group, $user, $message);
                } elseif ($group && $user->exists && !$user->isMemberOf($group)) {
                    $this->debug('User exists BUT is not member of group, bouncing and inviting');
                    $this->processGroupExistsButUserIsNotMember($group, $user, $message);
                } else {
                    if (!$user->exists) {
                        $this->debug('User does not exist, moving email to user_not_found');
                        $this->moveMessage($message, 'user_not_found');
                    } elseif (!$group) {
                        $this->debug('Group does not exist, moving email to group_not_found');
                        $this->moveMessage($message, 'group_not_found');
                    } elseif (!$discussion) {
                        $this->debug('Discussion does not exist, moving email to discussion_not_found');
                        $this->moveMessage($message, 'discussion_not_found');
                    }
                }
            } catch (Exception $exception) {
                $this->line('error processing message ');
            }
            $this->connection->expunge();
        }
    }


    /**
     * Small helper debug output
     */
    public function debug($message)
    {
        $this->line($message);
    }


    /**
     * Move the provided $message to a folder named $folder
     */
    public function moveMessage(ImapMessage $message, $folder)
    {
        if ($this->dry) {
            $this->info("The message would have been moved to folder " . $folder . " (dry run)");
            return true;
        }
        if ($this->connection->hasMailbox($folder)) {
            $folder = $this->connection->getMailbox($folder);
        } else {
            $folder = $this->connection->createMailbox($folder);
        }

        return $message->move($folder);
    }

    /**
     * Tries to find a valid user in the $message (using from: email header)
     * Else returns a new user with the email already set
     */
    public function extractUserFromMessage(ImapMessage $message)
    {
        $user = User::where('email', $message->getFrom()->getAddress())->firstOrNew();
        if (!$user->exists) {
            $user->email = $message->getFrom()->getAddress();
            $this->debug('User does not exist, created from: '  . $user->email);
        } else {
            $this->debug('User exists, email: '  . $user->email);
        }

        return $user;
    }

    /**
     * tries to find a valid group in the $message (using to: email header)
     *
     */
    public function extractGroupFromMessage(ImapMessage $message)
    {
        $recipients = $this->extractRecipientsFromMessage($message);

        foreach ($recipients as $to_email) {
            // remove prefix and suffix to get the slug we need to check against
            $to_email = str_replace(config('agorakit.inbox_prefix'), '', $to_email);
            $to_email = str_replace(config('agorakit.inbox_suffix'), '', $to_email);

            $to_emails[] = $to_email;

            $this->debug('(group candidate) to: '  . $to_email);
        }

        if (isset($to_emails)) {
            $group = Group::whereIn('slug', $to_emails)->first();
        } else {
            return false;
        }

        if ($group) {
            $this->debug('group found : ' . $group->name . ' (' . $group->id . ')');
            return $group;
        }
        $this->debug('group not found');
        return false;
    }


    // tries to find a valid discussion in the $message (using to: email header and message content)
    public function extractDiscussionFromMessage(ImapMessage $message)
    {
        $recipients = $this->extractRecipientsFromMessage($message);

        foreach ($recipients as $to_email) {
            // try to find a discussion with reply-[id] pattern
            $preg = "#" .  'reply-(\d+)' . config('agorakit.inbox_suffix') . "#";
            preg_match($preg, $to_email, $matches);

            if (isset($matches[1])) {
                $discussion = Discussion::where('id', $matches[1])->first();
                if ($discussion) {
                    $this->debug('discussion found with id ' . $discussion->id);
                    return $discussion;
                }
            }

            // try to find a comment with comment-[id] pattern
            $preg = "#" .  'comment-(\d+)' . config('agorakit.inbox_suffix') . "#";
            preg_match($preg, $to_email, $matches);

            if (isset($matches[1])) {
                $comment = Comment::where('id', $matches[1])->first();
                if ($comment) {
                    $this->debug('comment found with id ' . $comment->id);
                    return $comment->discussion;
                }
            }
        }

        $this->debug('discussion not found');
        return false;
    }

    /**
     * Returns a rich text represenation of the email, stripping away all quoted text, signatures, etc...
     */
    protected function extractTextFromMessage(ImapMessage $message)
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
     * Returns all recipients form the message, in the to: cc: references: and in-reply-to: fields
     */
    protected function extractRecipientsFromMessage(ImapMessage $message)
    {
        $recipients = [];
        foreach ($message->getTo() as $to) {
            $recipients[] = $to->getAddress();
        }

        foreach ($message->getCc() as $to) {
            $recipients[] = $to->getAddress();
        }

        foreach ($message->getInReplyTo() as $to) {
            $recipients[] = $to;
        }

        foreach ($message->getReferences() as $to) {
            $recipients[] = $to;
        }


        foreach ($recipients as $recipient) {
            $this->debug('potential recipients for this mail ' . $recipient);
        }

        return $recipients;
    }


    /**
     * Returns all headers of the email as key => value
     */
    protected function parseRFC822Headers(string $header_string): array
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
    public function isMessageAutomated(ImapMessage $message)
    {
        $message_headers = $this->parseRFC822Headers($message->getRawHeaders());

        if (array_key_exists('Auto-Submitted', $message_headers)) {
            return true;
        }

        if (array_key_exists('Auto-submitted', $message_headers)) {
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



    public function processGroupExistsAndUserIsMember(Group $group, User $user, ImapMessage $message)
    {
        $discussion = new Discussion();

        $discussion->name = $message->getSubject();
        $discussion->body = $this->extractTextFromMessage($message);

        $discussion->total_comments = 1; // the discussion itself is already a comment
        $discussion->user()->associate($user);

        if ($this->dry) {
            $this->info('Discussion would have been created ' . $discussion);
            return true;
        }

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


    public function processDiscussionExistsAndUserIsMember(Discussion $discussion, User $user, ImapMessage $message)
    {
        $comment = new \App\Comment();
        $comment->body = $this->extractTextFromMessage($message);
        $comment->user()->associate($user);
        if ($this->dry) {
            $this->info('Comment would have been created ' . $comment);
            return true;
        }

        if ($discussion->comments()->save($comment)) {
            $discussion->total_comments++;
            $discussion->save();

            // update activity timestamp on parent items
            $discussion->group->touch();
            $discussion->touch();
            $this->moveMessage($message, 'processed');
            $this->info('Comment has been created with id : ' . $comment->id);
            return true;
        } else {
            $this->moveMessage($message, 'comment_not_created');
            return false;
        }
    }


    public function processGroupExistsButUserIsNotMember(Group $group, User $user, ImapMessage $message)
    {
        Mail::to($user)->send(new MailBounce($message, 'You are not member of ' . $group->name . ' please join the group first before posting'));
        $this->moveMessage($message, 'bounced');
    }


    public function processGroupNotFoundUserNotFound(User $user, ImapMessage $message)
    {
        $this->moveMessage($message, 'group_not_found_user_not_found');
    }
}
