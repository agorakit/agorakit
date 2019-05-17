<?php

namespace App\Policies;

use App\Group;
use App\Membership;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembershipPolicy
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

    // a super admin can do everything
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    // a group admin can edit user memberships
    // a user can edit his/her own membership
    public function edit(User $user, Membership $membership)
    {
        if ($user->isAdminOf($membership->group)) {
            return true;
        }
        if ($user->id == $membership->user->id) {
            return true;
        }

        return false;
    }


    // a user can leave a group, a group admin can remove a member
    public function delete(User $user, Membership $membership)
    {
        if ($user->isAdminOf($membership->group)) {
            return true;
        }
        if ($user->id == $membership->user->id) {
            return true;
        }

        return false;
    }
}
