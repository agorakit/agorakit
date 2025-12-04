<?php

namespace App\Policies;

use App\Discussion;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscussionPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function view(?User $user, Discussion $discussion)
    {
        if ($discussion->group->isOpen()) {
            return true;
        }

        if ($user) {
            return $user->isMemberOf($discussion->group);
        }
    }

    public function update(User $user, Discussion $discussion)
    {
        return $user->isMemberOf($discussion->group);
    }

    public function delete(User $user, Discussion $discussion)
    {
        return $user->isAdminOf($discussion->group);
    }

    public function history(User $user, Discussion $discussion)
    {
        return $user->isMemberOf($discussion->group);
    }

    public function pin(User $user, Discussion $discussion)
    {
        return $user->isAdminOf($discussion->group);
    }

    public function archive(User $user, Discussion $discussion)
    {
        return $user->isAdminOf($discussion->group);
    }

    public function react(User $user, Discussion $discussion)
    {
        return $user->isMemberOf($discussion->group);
    }
}
