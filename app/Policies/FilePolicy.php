<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
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
        if ($file->group->isOpen()) {
            return true;
        }

        if ($user) {
            return $user->isMemberOf($file->group);
        }
    }

    public function update(User $user, File $file)
    {
        if ($user->isAdminOf($file->group)) {
            return true;
        }

        return $user->id == $file->user_id;
    }

    public function delete(User $user, File $file)
    {
        if ($user->isAdminOf($file->group)) {
            return true;
        }

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

    public function pin(User $user, File $file)
    {
        return $user->isAdminOf($file->group);
    }

    public function archive(User $user, File $file)
    {
        return $user->isAdminOf($file->group);
    }
}
