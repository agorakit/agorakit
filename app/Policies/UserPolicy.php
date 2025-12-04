<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy extends BasePolicy
{
    use HandlesAuthorization;

    /**
     * Setting let us define if users can be created
     */
    public function create(?User $user)
    {
        return setting('user_can_register', true);
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
