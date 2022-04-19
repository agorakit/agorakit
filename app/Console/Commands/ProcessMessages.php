<?php

namespace App\Console\Commands;

use App\Discussion;
use App\Group;
use App\Mail\MailBounce;
use App\Message;
use App\User;
use EmailReplyParser\EmailReplyParser;
use Illuminate\Console\Command;
use League\HTMLToMarkdown\HtmlConverter;
use Log;
use Mail;
use Michelf\Markdown;
use ZBateson\MailMimeParser\Header\HeaderConsts;

/*
Inbound Email handler for Agorakit, second step : processing the messages table

Read this issue : https://github.com/agorakit/agorakit/issues/371

For each message in the messages table, this command tries to handle the message and create comments or discussions when possible
A log of each message and their status is kept

1. Sending email to a group
- The user exists
- The group exists
- The user is a member of the group

2. Replying to a discussion
- discussion exists
- user is a member of the group

In all cases the mail is processed
Bounced to user in case of failure and known user


Emails are generated as follow :

[INBOX_PREFIX][group-slug][INBOX_SUFFIX]

[INBOX_PREFIX]reply-[discussion-id][INBOX_SUFFIX]

Prefix and suffix is defined in the .env file


This is long and monolithic but it does the job

*/

class ProcessMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agorakit:processmessages
    {{--debug : Show debug info.}}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the messages table to allow post by email functionality';

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

        $this->debug(Message::where('status', Message::CREATED)->count().' messages to handle');

        $messages = Message::where('status', Message::CREATED)->get(); // process only those with the "created" status
        foreach ($messages as $message) {
            $this->line('---------------------------------------------------');

            $this->line('Processing message id '.$message->id);

            // check if message is automated
            if ($message->isAutomated()) {
                $this->info('Message is automated');
                $message->status = Message::AUTOMATED;
                $message->save();
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
                continue;
            }

            if ($group && $user->exists && $user->isMemberOf($group)) {
                $this->info('User exists and is member of group, creating new discussion');
                $this->processGroupExistsAndUserIsMember($group, $user, $message);
                continue;
            }

            if ($group && $user->exists && ! $user->isMemberOf($group)) {
                $this->info('User exists BUT is not member of group, bouncing and inviting to join');
                $this->processGroupExistsButUserIsNotMember($group, $user, $message);
                continue;
            }

            // TODO handle the case of user exists but group doesn't -> might be a good idea to bounce back to user

            // else mark the message as invalid
            $this->info('Message is invalid / cannot find a valid recipient');
            $message->status = Message::INVALID;
            $message->save();
            continue;
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
        $email = $message->parse()->getHeader('From')->getEmail();

        $user = User::where('email', $email)->firstOrNew();
        if (! $user->exists) {
            $user->email = $email;
            $this->debug('User does not exist, created from: '.$email);
        } else {
            $this->debug('User exists, email: '.$email);
        }

        return $user;
    }

    // tries to find a valid group in the $message (using to: email header)
    public function extractGroupFromMessage(Message $message)
    {
        $recipients = $message->extractRecipients();
        $to_emails = [];

        foreach ($recipients as $to_email) {
            // remove prefix and suffix to get the slug we need to check against
            $to_email = str_replace(config('agorakit.inbox_prefix'), '', $to_email);
            $to_email = str_replace(config('agorakit.inbox_suffix'), '', $to_email);

            $to_emails[] = $to_email;

            $this->debug('(group candidate) to: '.$to_email);
        }

        $group = Group::whereIn('slug', $to_emails)->first();

        if ($group) {
            $this->debug('group found : '.$group->name.' ('.$group->id.')');

            return $group;
        }
        $this->debug('group not found');

        return false;
    }

    // tries to find a valid discussion in the $message (using to: email header and message content)
    public function extractDiscussionFromMessage(Message $message)
    {
        $recipients = $message->extractRecipients();

        foreach ($recipients as $to_email) {
            preg_match('#'.config('agorakit.inbox_prefix').'reply-(\d+)'.config('agorakit.inbox_prefix').'#', $to_email, $matches);
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

    public function processGroupExistsAndUserIsMember(Group $group, User $user, Message $message)
    {
        $discussion = new Discussion();

        $discussion->name = $message->subject;
        $discussion->body = $message->extractText();

        $discussion->total_comments = 1; // the discussion itself is already a comment
        $discussion->user()->associate($user);

        if ($group->discussions()->save($discussion)) {
            // update activity timestamp on parent items
            $group->touch();
            $user->touch();
            $this->info('Discussion has been created with id : '.$discussion->id);
            $this->info('Title : '.$discussion->name);
            Log::info('Discussion has been created from email', ['message' => $message, 'discussion' => $discussion]);

            // associate the message with the created content
            $message->discussion()->associate($discussion);
            $message->group()->associate($discussion->group);
            $message->user()->associate($user);
            $message->status = Message::POSTED;
            $message->save();

            return true;
        } else {
            $message->status = Message::ERROR;
            $message->save();
            Mail::to($user)->send(new MailBounce($message, 'Your discussion could not be created in the group, maybe your message was empty'));

            return false;
        }
    }

    public function processDiscussionExistsAndUserIsMember(Discussion $discussion, User $user, Message $message)
    {
        $comment = new \App\Comment();
        $comment->body = $message->extractText();
        $comment->user()->associate($user);
        if ($discussion->comments()->save($comment)) {
            $discussion->total_comments++;
            $discussion->save();

            // update activity timestamp on parent items
            $discussion->group->touch();
            $discussion->touch();

            // associate the message with the created content
            $message->discussion()->associate($discussion);
            $message->group()->associate($discussion->group);
            $message->user()->associate($user);

            $message->status = Message::POSTED;
            $message->save();

            return true;
        } else {
            $message->status = Message::ERROR;
            $message->save();

            return false;
        }
    }

    public function processGroupExistsButUserIsNotMember(Group $group, User $user, Message $message)
    {
        Mail::to($user)->send(new MailBounce($message, 'You are not member of '.$group->name.' please join the group first before posting'));
        $message->status = Message::BOUNCED;
        $message->save();

        return true;
    }

    public function processGroupNotFoundUserNotFound(User $user, Message $message)
    {
        return true;
    }
}
