<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use \App\Comment;
use \App\User;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function update(User $user, Comment $comment)
    {
        return $user->id === $comment->user_id;
    }
}
