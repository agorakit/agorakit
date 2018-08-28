<?php

namespace App\Listeners;

use App\Events\ContentCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
    * @param  ContentCreated  $event
    * @return void
    */
    public function handle(ContentCreated $event)
    {


        // we curently only handle comments, but in the future this listener could work on discussions or wathever
        if ($event->model instanceof \App\Comment)
        {
            $comment = $event->model;
            preg_match_all(  "#(?<!\w)@([\w_\-\.]+)#", $comment->body, $matches);

            $users_to_mention = [];

            foreach ($matches[0] as $username)
            {
                $username = substr($username, 1);
                // we find users only in the group from where the mention was made
                $user = $comment->discussion->group->users->where('username', $username)->first();
                if ($user->exists) {
                    Notification::send($user, new \App\Notifications\Mention($comment, \Auth::user()));
                }
            }
        }
    }

}
