<?php

namespace App\Policies;

use App\Group;
use App\Membership;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembershipPolicy extends BasePolicy
{
    use HandlesAuthorization;

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
