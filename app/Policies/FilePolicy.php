<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use \App\File;
use \App\User;

class FilePolicy
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


    public function update(User $user, File $file)
    {
        return $user->id === $file->user_id;
    }


    public function delete(User $user, File $file)
    {
        return $user->id === $file->user_id;
    }

}
