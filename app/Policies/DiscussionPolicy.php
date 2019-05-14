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


    public function view(?User $user, File $file)
    {
        if ($user) {
            return $user->isMemberOf($group);
        } else {
            return ($action->group->isOpen());
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
