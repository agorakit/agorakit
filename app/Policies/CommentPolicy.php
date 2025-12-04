<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy extends BasePolicy
{
    use HandlesAuthorization;

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
