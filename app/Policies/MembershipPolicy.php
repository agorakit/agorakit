<?php

namespace App\Policies;

use App\Group;
use App\Membership;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembershipPolicy extends BasePolicy
{
    use HandlesAuthorization;
    
    public function edit(User $user, Membership $membership)
    {
        // a group admin can edit user memberships
        if ($user->isAdminOf($membership->group)) {
            return true;
        }

        // a user can edit his/her own membership
        if ($user->id == $membership->user->id) {
            return true;
        }

        return false;
    }

    /**
     * Handles wether a $user can destroy it's membership
     */
    public function delete(User $user, Membership $membership)
    {
        // a group admin can always remove a member
        if ($user->isAdminOf($membership->group)) {
            return true;
        }

        // a user that is less than invited cannot change delete the membership anymore
        if ($membership->membership < 0) {
            return false;
        }

        // a user can leave a group, 
        if ($user->id == $membership->user->id) {
            return true;
        }

        return false;
    }
}
