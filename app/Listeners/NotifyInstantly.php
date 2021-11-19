<?php

namespace App\Listeners;

use App\Events\ContentCreated;
use Notification;
use App\Comment;
use App\User;
use App\Discussion;

class NotifyInstantly
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

            // get a list of users that must be notified instantly
            $users = $comment->discussion->group->users()->where('notification_interval', 1)->get();

            //dd ($users);

            foreach ($users as $user) {
                // Notification::send($user, new \App\Notifications\MentionedUser($comment, \Auth::user()));
            }
        }
    }
}
