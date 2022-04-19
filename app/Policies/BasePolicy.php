<?php

namespace App\Policies;

use App\Group;
use App\Membership;
use App\User;

/**
 * This policy provides a base method used by other policies.
 */
class BasePolicy
{
    /**
     * Returns a collection of permissions for the $user in the $group.
     */
    public function getPermissionsFor(User $user, Group $group)
    {
        // load membership for this user in the group
        $membership = Membership::where('user_id', '=', $user->id)->where('group_id', '=', $group->id)->first();

        // return empty collection if no memberhsip found
        if (! $membership) {
            return collect();
        }

        // ? needed ?
        if (! $membership->exists) {
            return collect();
        }

        // handle members
        if ($membership->membership == Membership::MEMBER) {
            // check if the groups has custom permissions enabled
            if ($group->getSetting('custom_permissions', false)) {
                $permissions = $group->getSetting('permissions');

                return collect($permissions['member']);
            } else {
                // return default permissions for members
                return collect(['create-discussion', 'create-action', 'create-file', 'invite']);
            }
        }

        // handle admins
        if ($membership->membership == Membership::ADMIN) {
            // currently we return all possible permissions for group admins
            return collect(['create-discussion', 'create-action', 'create-file', 'invite']);

            // check if the groups has custom permissions enabled
            if ($group->getSetting('custom_permissions', false)) {
                $permissions = $group->getSetting('permissions');

                return collect($permissions['admin']);
            } else {
                // return default permissions for admins
                return collect(['create-discussion', 'create-action', 'create-file', 'invite']);
            }
        }

        // in all other cases : empty permissions
        return collect();
    }
}
