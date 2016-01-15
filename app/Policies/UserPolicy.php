<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use \App\User;


class UserPolicy
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


    public function update(User $user, User $user2)
    {
        return $user->id === $user2->id;
    }


    public function delete(User $user, User $user2)
    {
        return $user->id === $user2->id;
    }

}
