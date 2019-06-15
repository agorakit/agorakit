<?php

namespace App\Policies;

use App\Membership;
use App\User;
use App\Group;
use Illuminate\Auth\Access\HandlesAuthorization;


/**
 * This policy provides a base method used by other policies
 */
class BasePolicy
{
    /**
    * Returns a collection of permissions for the $user in the $group
    */
    public function getPermissionsFor(User $user, Group $group)
    {
        // load membership for this user in the group
        $membership = Membership::where('user_id', '=', $user->id)->where('group_id', '=', $group->id)->first();

        // return empty collection if no memberhsip found
        if (!$membership->exists)
        {
            return collect();
        }

        // check if the groups has custom permissions enabled
        if ($group->getSetting('custom_permissions', false))
        // get the permissions for this membership level from the group


        // handle members

        // handle admins

    }
}
