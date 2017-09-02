<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use \App\Discussion;
use \App\User;

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


    public function update(User $user, Discussion $discussion)
    {
        return $user->isMemberOf($discussion->group);
    }

    public function delete(User $user, Discussion $discussion)
    {
        return ($user->isAdminOf($discussion->group));
    }

}
