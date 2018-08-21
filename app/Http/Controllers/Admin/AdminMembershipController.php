<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
* Admin features to make other users admin of a group.
*/
class AdminMembershipController extends Controller
{

    /**
    * Set a member of a group to admin (admin feature).
    */
    public function store(Request $request, Group $group, User $user)
    {
        $this->authorize('edit-membership', $group);

        $membership = \App\Membership::where(['user_id' => $user->id, 'group_id' => $group->id])->firstOrFail();
        $membership->membership = \App\Membership::ADMIN;
        $membership->save();
        flash(trans('messages.user_made_admin_successfuly').' : '.$user->name);

        return redirect()->route('groups.users.index', $group);
    }

    /**
    * Set a member of a group to normal user (admin feature).
    */
    public function destroy(Request $request, Group $group, User $user)
    {
        $this->authorize('edit-membership', $group);

        $membership = \App\Membership::where(['user_id' => $user->id, 'group_id' => $group->id])->firstOrFail();
        $membership->membership = \App\Membership::MEMBER;
        $membership->save();
        flash(trans('messages.user_made_member_successfuly').' : '.$user->name);

        return redirect()->route('groups.users.index', $group);
    }


}
