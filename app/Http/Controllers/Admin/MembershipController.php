<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
* Admin features to act on membership and to make other users admin of a group.
*/
class MembershipController extends Controller
{
    /**
    * Force add a member to a group (admin feature)
    * This is the form that allows an admin to select a user to add to a group.
    */
    public function addUserForm(Request $request, Group $group)
    {
        $this->authorize('edit-membership', $group);

        // load a list of users not yet in this group
        $members = $group->users;
        $notmembers = \App\User::whereNotIn('id', $members->pluck('id'))->orderBy('name')->pluck('name', 'id');

        return view('membership.add')
        ->with('group', $group)
        ->with('members', $members)
        ->with('notmembers', $notmembers)
        ->with('tab', 'users');
    }

    /**
    * Force add a member to a group (admin feature)
    * Processing form's content.
    */
    public function addUser(Request $request, Group $group)
    {
        $this->authorize('edit-membership', $group);

        if ($request->has('users')) {
            foreach ($request->get('users') as $user_id) {
                $user = \App\User::findOrFail($user_id);
                // load or create membership for this group and user combination
                $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $group->id]);
                $membership->membership = \App\Membership::MEMBER;
                $membership->notification_interval = intervalToMinutes('weekly'); // this is a sane default imho for notification interval

                // we prented the user has been already notified once, now. The first mail sent will be at the choosen interval from now on.
                $membership->notified_at = Carbon::now();
                $membership->save();

                // notify the user
                $user->notify(new \App\Notifications\AddedToGroup($group));

                flash(trans('messages.user_added_successfuly').' : '.$user->name);
            }
        }

        return redirect()->route('groups.users.index', $group);
    }

    /**
    * Force remove a member to a group (admin feature)
    * This is must be called from a delete form.
    */
    public function removeUser(Request $request, Group $group, User $user)
    {
        $this->authorize('edit-membership', $group);
        $membership = \App\Membership::where(['user_id' => $user->id, 'group_id' => $group->id])->firstOrFail();
        $membership->membership = \App\Membership::REMOVED;
        $membership->save();
        flash(trans('messages.user_removed_successfuly').' : '.$user->name);

        return redirect()->route('groups.users.index', $group);
    }

    public function editUserForm(Request $request, Group $group, User $user)
    {
        $this->authorize('edit-membership', $group);

        return view('membership.admin')
        ->with('group', $group)
        ->with('user', $user)
        ->with('tab', 'users');
    }

    /**
    * Set a member of a group to admin (admin feature).
    */
    public function addAdminUser(Request $request, Group $group, User $user)
    {
        $this->authorize('edit-membership', $group);

        $membership = \App\Membership::where(['user_id' => $user->id, 'group_id' => $group->id])->firstOrFail();
        $membership->membership = \App\Membership::ADMIN;
        $membership->save();
        flash(trans('messages.user_made_admin_successfuly').' : '.$user->name);

        return redirect()->route('groups.users.index', $group);
    }

    /**
    * Set a member of a group to admin (admin feature).
    */
    public function removeAdminUser(Request $request, Group $group, User $user)
    {
        $this->authorize('edit-membership', $group);

        $membership = \App\Membership::where(['user_id' => $user->id, 'group_id' => $group->id])->firstOrFail();
        $membership->membership = \App\Membership::MEMBER;
        $membership->save();
        flash(trans('messages.user_made_member_successfuly').' : '.$user->name);

        return redirect()->route('groups.users.index', $group);
    }


    /**
    * Allow an admin to confirm a membership application
    */
    public function confirm(Request $request, Group $group, User $user)
    {
        $this->authorize('edit-membership', $group);

        $membership = \App\Membership::where(['user_id' => $user->id, 'group_id' => $group->id])->firstOrFail();
        $membership->membership = \App\Membership::MEMBER;
        $membership->save();
        flash(trans('messages.user_made_member_successfuly').' : '.$user->name);

        return redirect()->route('groups.users.index', $group);
    }


}
