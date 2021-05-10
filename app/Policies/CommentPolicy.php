<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function update(User $user, Comment $comment)
    {
        return $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment)
    {
        if ($user->isAdminOf($comment->discussion->group)) {
            return true;
        }

        return $user->id === $comment->user_id;
    }

    public function history(User $user, Comment $comment)
    {
        return $user->isMemberOf($comment->discussion->group);
    }

    public function react(User $user, Comment $comment)
    {
        return $user->isMemberOf($comment->discussion->group);
    }
}
