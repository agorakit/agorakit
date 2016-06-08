<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use \App\Group;
use \App\User;

class GroupPolicy
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
   * Determine if the given post can be updated by the user.
   *
   * @param  \App\User  $user
   * @param  \App\Post  $post
   * @return bool
   */
   public function update(User $user, Group $group)
   {
      return $user->isMemberOf($group);
   }




   /*
   the following functions let us decide if a user can or cannot creat some stuff in a group
   Curently it's based on the fact that you are an active member of the group
   */

   public function createDiscussion(User $user, Group $group)
   {
      return $user->isMemberOf($group);
   }

   public function createFile(User $user, Group $group)
   {
      return $user->isMemberOf($group);
   }


   public function createAction(User $user, Group $group)
   {
      return $user->isMemberOf($group);
   }

   public function createComment(User $user, Group $group)
   {
      return $user->isMemberOf($group);
   }


   public function invite(User $user, Group $group)
   {
      return $user->isMemberOf($group);
   }


   public function join(User $user, Group $group)
   {
     // if group is open anyone can join, else it's invite only
     if ($group->group_type == $group::OPEN)
     {
        return true;
     }
     elseif ($group->group_type == $group::CLOSED)
     {
        // do we have an invite already for this group and user?
        $invite = \App\Invite::where('email', $user->email)->where('group_id', $group->id)->count();
        if ($invite == 1)
        {
          return true;
        }
      }
      return false;
    }



}
