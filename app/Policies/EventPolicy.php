<?php

namespace App\Policies;

use App\Event;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
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
     * Determine whether the user can view the event.
     *
     * @param \App\User   $user
     * @param \App\Event $event
     *
     * @return mixed
     */
    public function view(?User $user, Event $event)
    {
        if ($event->group->isOpen()) {
            return true;
        }

        if ($event->isPublic()) {
            return true;
        }

        if ($user) {
            return $user->isMemberOf($event->group);
        }
    }

    public function update(User $user, Event $event)
    {
        if ($user->isAdminOf($event->group)) {
            return true;
        }

        return $user->id === $event->user_id;
    }

    public function delete(User $user, Event $event)
    {
        if ($user->isAdminOf($event->group)) {
            return true;
        }

        return $user->id === $event->user_id;
    }

    public function history(?User $user, Event $event)
    {
        if ($user) {
            return $user->isMemberOf($event->group);
        } else {
            return $event->group->isOpen();
        }
    }

    /** 
     * Defines if a user can participate or not or maybe to an event
     */
    public function participate(User $user, Event $event)
    {
        return $user->isMemberOf($event->group);
    }
}
