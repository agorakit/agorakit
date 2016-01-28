<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use \App\Action;
use \App\User;

class ActionPolicy
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

  /*
  public function update(User $user, Action $action)
  {
    return $user->id === $action->user_id;
  }
  */


  public function delete(User $user, Action $action)
  {
    return $user->id === $action->user_id;
  }
}
