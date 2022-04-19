<?php

namespace App\Policies;

use App\Action;
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
     * @param \App\User   $user
     * @param \App\Action $action
     *
     * @return mixed
     */
    public function view(?User $user, Action $action)
    {
        if ($action->group->isOpen()) {
            return true;
        }

        if ($user) {
            return $user->isMemberOf($action->group);
        }
    }

    public function update(User $user, Action $action)
    {
        if ($user->isAdminOf($action->group)) {
            return true;
        }

        return $user->id === $action->user_id;
    }

    public function delete(User $user, Action $action)
    {
        if ($user->isAdminOf($action->group)) {
            return true;
        }

        return $user->id === $action->user_id;
    }

    public function history(?User $user, Action $action)
    {
        if ($user) {
            return $user->isMemberOf($group);
        } else {
            return $action->group->isOpen();
        }
    }

    /**
     * Defines if a user can participate or not or maybe to an event
     */
    public function participate(User $user, Action $action)
    {
        return $user->isMemberOf($action->group);
    }
}
