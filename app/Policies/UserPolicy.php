<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    // A user can edit his/her own profile
    public function update(User $user, User $user2)
    {
        return $user->id == $user2->id;
    }

    // A user can delete his/her own profile
    public function delete(User $user, User $user2)
    {
        return $user->id == $user2->id;
    }
}
