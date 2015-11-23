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
      return $user->id === $post->user_id; // TODO
   }

   public function createDiscussion(User $user, Group $group)
   {
      return $user->isMemberOf($group);
      
   }


}
