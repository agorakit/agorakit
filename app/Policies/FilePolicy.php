<?php

namespace App\Policies;

use App\File;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(?User $user, File $file)
    {
        if ($user) {
            return $user->isMemberOf($file->group);
        } else {
            return $file->group->isOpen();
        }
    }

    public function update(User $user, File $file)
    {
        if ($user->isAdminOf($file->group)) {
            return true;
        }

        return $user->isMemberOf($file->group);
    }

    public function delete(User $user, File $file)
    {
        return $user->id == $file->user_id;
    }

    public function download(User $user, File $file)
    {
        if ($file->group->isOpen()) {
            return true;
        }
        if ($user->isMemberOf($file->group)) {
            return true;
        }

        return false;
    }
}
