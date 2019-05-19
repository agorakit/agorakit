<?php

namespace App\Policies;

use App\Discussion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscussionPolicy
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

    public function view(?User $user, Discussion $discussion)
    {
        if ($discussion->group->isOpen()) {
            return true;
        }

        if ($user) {
            return $user->isMemberOf($discussion->group);
        }
    }

    public function update(User $user, Discussion $discussion)
    {
        return $user->isMemberOf($discussion->group);
    }

    public function delete(User $user, Discussion $discussion)
    {
        return $user->isAdminOf($discussion->group);
    }

    public function history(User $user, Discussion $discussion)
    {
        return $user->isMemberOf($discussion->group);
    }
}
