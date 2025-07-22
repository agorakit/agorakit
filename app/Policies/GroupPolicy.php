<?php

namespace App\Policies;

use App\Group;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * This policy is the most important one. It defines what one can and cannot do in a group.
 * It is used everywhere to check for user abilities.
 *
 * It uses the newish laravel policy for anonymous users
 * (user object can be null, in this case the policy is for unauthenticated user)
 *
 * Policies will replace almost all middleware at some point,
 * because this way we have a single place to write sensitive code.
 * The policies can be used in controllers, views, etc...
 *
 *
 * The BasePolicy class provides common methods used in other policies
 *
 * !! This is sensitive code !!
 * --> Peer review appreciated <--
 *
 */

class GroupPolicy extends BasePolicy
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

    /**
     * A super admin can do everything, this bypasses all the following code
     */
    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Viewing a group means reading title and presentation (= group home page).
     * Only secret groups are hidden from non members.
     */
    public function view(?User $user, Group $group)
    {
        if ($group->isSecret()) {
            if ($user) {
                if ($user->isMemberOf($group)) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    /**
     * A user can create a group if it is allowed in the global settings (set by admin-wide accounts)
     */
    public function create(User $user)
    {
        if (setting('user_can_create_groups') == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * A user can import a group if it is allowed in the global settings (set by admin-wide accounts)
     */
    public function import(User $user)
    {
        if (setting('user_can_import_groups') == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * A group admin can delete a group
     */
    public function delete(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }

    /**
     * A group admin can export group data
     */
    public function export(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }

    /**
     * A group admin can edit a group
     */
    public function update(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }

    /**
     *   Can the user administer the group or not?
     */
    public function administer(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }

    /*
    The following functions let us decide if a user can or cannot creat some stuff in a group
    Curently it's based on the fact that you are an active member of the group OR we use the admin defined permissions
    The function getPermissionsFor() is defined in the base class BasePolicy::getPermissionsFor()
    */

    public function createDiscussion(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-discussion');
    }

    public function createFile(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-file');
    }

    public function createLink(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-file');
    }

    public function createFolder(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-file');
    }

    public function createCalendarEvent(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-calendarevent');
    }

    public function createComment(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('create-discussion');
    }



    /**
     * Invite is also a customizable permission
     */
    public function invite(User $user, Group $group)
    {
        return $this->getPermissionsFor($user, $group)->contains('invite');
    }

    /**
     * Ability to "index" (list) group content
     * If there is a user we check that either the group is open, either the user is member of the group
     * If we have no user, we check if the group is open
     */
    public function viewDiscussions(?User $user, Group $group)
    {
        // isn't it lovely :
        if ($user) {
            return $group->isOpen() || $user->isMemberOf($group);
        }

        return $group->isOpen();
    }

    public function viewCalendarEvents(?User $user, Group $group)
    {
        if ($user) {
            return $group->isOpen() || $user->isMemberOf($group);
        }

        return $group->isOpen();
    }

    /**
     * Only show members to group members
     */
    public function viewMembers(?User $user, Group $group)
    {
        if ($user) {
            return $user->isMemberOf($group);
        }

        return false;
    }

    public function viewFiles(?User $user, Group $group)
    {
        if ($user) {
            return $group->isOpen() || $user->isMemberOf($group);
        }

        return $group->isOpen();
    }

    public function viewTags(?User $user, Group $group)
    {
        if ($user) {
            return $group->isOpen() || $user->isMemberOf($group);
        }

        return $group->isOpen();
    }




    /**
     * Admin stuff :
     * Group admins can manage tags
     */
    public function manageTags(?User $user, Group $group)
    {
        if ($user) {
            return $user->isAdminOf($group);
        }

        return false;
    }

    /**
     * Group admins can change group type
     */
    public function changeGroupType(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }

    public function manageMembership(User $user, Group $group)
    {
        return $user->isAdminOf($group);
    }



    public function history(User $user, Group $group)
    {
        return $user->isMemberOf($group);
    }


    public function join(User $user, Group $group)
    {
        if (!$user->isVerified()) {
            return false;
        }

        // if group is open anyone can join, else it's invite only
        if ($group->group_type == $group::OPEN) {
            return true;
        } elseif ($group->group_type == $group::CLOSED) {
            // do we have an invite already for this group and user?
            $invite = \App\Invite::where('email', $user->email)->where('group_id', $group->id)->count();
            if ($invite == 1) {
                return true;
            }
        }

        return false;
    }

    public function changeGroupStatus(User $user, Group $group)
    {
        return $user->isAdmin();
    }
}
