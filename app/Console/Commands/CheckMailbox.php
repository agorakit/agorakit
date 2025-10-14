<?php

namespace App\Console\Commands;

use App\Discussion;
use App\Comment;
use App\Group;
use App\User;
use Exception;
use App\Adapters\ImapMessage;
use App\Adapters\ImapServer;
use Illuminate\Console\Command;
use Log;
use Mail;
use App\Mail\MailBounce;
use League\HTMLToMarkdown\HtmlConverter;
use Michelf\Markdown;
use EmailReplyParser\EmailReplyParser;

/**
 * Inbound email handler for Agorakit, allows user to post content by email
 *
 * Everytime it's run, emails found in the defined inbox are processed
 * - find a sender in the existing users list or create a user
 * - find a recipient in the to and other mail header. It can a group or a discussion
 * - check the the user can post to this recipient
 * - process email content (filter) and create the appropriate content (discussion or comment)
 *
 * A catch-all email is required, or an email server supporting "+" addressing.
 *
 * Emails are generated as follows:
 *   [INBOX_PREFIX][group-slug][INBOX_SUFFIX]
 *   [INBOX_PREFIX]reply-[discussion-id][INBOX_SUFFIX]
 *
 * Prefix and suffix is defined in the .env file as well as the server credentials
 *
 * Only imap is supported since it allows to store all processed and failed emails in separate folders on the mail server for further inspection
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

    /**
     * Execute the console command.
     */
    public function handle(): bool
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

        if ($this->option('debug') && defined('LATT_NOSELECT')) {
            // Show all available mailboxes (if ext-imap exists).
            foreach (ImapServer::getInstance()->getMailboxes() as $mailbox) { // instance of \Ddeboer\Imap\Mailbox
                // Skip container-only mailboxes
                // @see https://secure.php.net/manual/en/function.imap-getmailboxes.php
                if ($mailbox->getAttributes() & \LATT_NOSELECT) {
                    continue;
                }
                $this->debug('Mailbox ' . $mailbox->getName() . ' has ' . $mailbox->count() . ' messages');
            }
        }

        // Retrieve messages from 'INBOX' mailbox.
        $messages = ImapServer::getInstance()->getMessages();
        $i = 0;
        foreach ($messages as $message) {
            try {
                $message = new ImapMessage($message);
                $this->debug('-----------------------------------');
                // Limit to 100 messages at a time
                if ($i > 100) {
                    break;
                }
                $i++;
                $this->debug('Processing email "' . $message->getSubject() . '"');
                $this->debug('From ' . $message->getFrom()->getAddress());

                // Discard automated messages
                if ($message->isAutomated()) {
                    $this->moveMessage($message, 'automated');
                    $this->debug('Message discarded because automated');
                    continue;
                }
                $this->processMessage($message);
            } catch (Exception $exception) {
                $this->line('error processing message ');
            }
            ImapServer::getInstance()->expunge();
        }
        return true;
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
    public function moveMessage(ImapMessage $message, string $folderName)
    {
        if ($this->dry) {
            $this->info("The message would have been moved to folder " . $folderName . " (dry run)");
            return true;
        }
        $folder = ImapServer::getInstance()->touchFolder($folderName);
        return $message->move($folder);
    }

    /**
     * Tries to find a valid user in the $message (using from: email header)
     */
    public function extractUserFromMessage(ImapMessage $message)
    {
        $user = User::where('email', $message->getFrom())->firstOrNew();
        if (!$user->exists) {
            $user->email = $message->getFrom();
            $this->debug('User did not exist, created from email: '  . $user->email);
        } else {
            $this->debug('User exists, email: '  . $user->email);
        }

        return $user;
    }

    /**
     * Tries to find a valid group in the $message (using to: email header)
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

    /**
     * Tries to find a valid discussion in the $message (using to: email header and message content)
     */
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
     * Returns a rich text representation of the email, stripping away all quoted text, signatures, etc...
     */
    protected function extractTextFromMessage(ImapMessage $message)
    {
        $body_html = $message->getBodyHtml(); // Raw HTML content
        $body_text = nl2br(EmailReplyParser::parseReply($message->getBodyText()));

        // Count characters in plain text. If < 5, post the whole HTML mess converted to Markdown,
        // then stripped with the same EmailReplyParser, then converted from Markdown back to HTML, pfeeew
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
        $recipients = $message->getRecipients();

        foreach ($recipients as $recipient) {
            $this->debug('potential recipients for this mail ' . $recipient);
        }

        return $recipients;
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

    /**
     * @param ImapMessage $message
     */
    protected function processMessage(ImapMessage $message): void
    {
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
    }
}
