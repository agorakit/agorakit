<?php

namespace App\Policies;

use App\Action;
use App\Group;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActionPolicy
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

    /**
    * Determine whether the user can view the action.
    *
    * @param  \App\User  $user
    * @param  \App\Action  $action
    * @return mixed
    */
    public function view(?User $user, Action $action)
    {
        if ($user) {
            return $user->isMemberOf($group);
        } else {
            return ($action->group->isOpen());
        }
    }

    /*
        public function create(User $user, Group $group)
        {
            return $user->isMemberOf($group);
        }
    */


    public function update(User $user, Action $action)
    {
        return $user->id === $action->user_id;
    }

    public function delete(User $user, Action $action)
    {
        return $user->id === $action->user_id;
    }

    public function history(?User $user, Action $action)
    {
        if ($user) {
            return $user->isMemberOf($group);
        } else {
            return ($action->group->isOpen());
        }
    }
}
