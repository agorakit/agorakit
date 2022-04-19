<?php

namespace App\Listeners;

use App\Comment;
use App\Discussion;
use App\Events\ContentCreated;
use App\User;
use Notification;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ContentCreated $event
     *
     * @return void
     */
    public function handle(ContentCreated $event)
    {

        // Comments
        if ($event->model instanceof Comment) {
            $comment = $event->model;

            $users = $this->findUsers($comment->body);

            foreach ($users as $user) {
                if ($user->isMemberOf($comment->discussion->group)) {
                    Notification::send($user, new \App\Notifications\MentionedUser($comment, \Auth::user()));
                    flash($user->name.' '.trans('messages.notified'));
                }
            }
        }
    }

    /**
     * Finds users to mention in a string, looks for @username style mentions in the string an returns users models
     */
    public function findUsers($body)
    {
        preg_match_all("#(?<!\w)@([\w_\-\.]+)#", $body, $matches);

        $users = [];

        // dedupe matches
        $matches = array_unique($matches[0]);

        $usernames = collect();
        foreach ($matches as $username) {
            // remove @ char
            $usernames->push(substr($username, 1));
        }
        // find user
        $users = User::whereIn('username', $usernames)->get();

        return $users;
    }
}
