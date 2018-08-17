<?php
namespace App\Traits;

use Auth;
use Notification;
use App\Comment;

trait MentionUsers
{
    public static function bootMentionUsers()
    {
        static::created(function ($model) {
            if ($model instanceof Comment) { // we curently only support comments
            // Find users to notify
                $dom = new \DOMDocument;
                libxml_use_internal_errors(true);
                $dom->loadHTML($model->body);
                libxml_clear_errors();

                $users_to_mention = [];

                foreach ($dom->getElementsByTagName('a') as $tag) {
                    foreach ($tag->attributes as $attribName => $attribNodeVal) {
                        if ($attribName == 'data-mention-user-id') {
                            $users_to_mention[] = $tag->getAttribute($attribName);
                        }
                    }
                }
            

                // if we found some users to mention
                if (count($users_to_mention) > 0) {
                    $users = ($model->discussion->group->users->find($users_to_mention)); // we find users only in the group from where the mention appered to avoid abuse
                    Notification::send($users, new \App\Notifications\Mention($model, \Auth::user()));
                }
            }
        });
    }
}
