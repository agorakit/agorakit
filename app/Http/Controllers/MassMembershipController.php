<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Admin features to act on membership.
 */
class MassMembershipController extends Controller
{
    /**
     * Force add a member to a group (admin feature)
     * This is the form that allows an admin to select a user to add to a group.
     */
    public function create(Request $request, Group $group)
    {
        $this->authorize('manage-membership', $group);

        // load a list of users not yet in this group
        $members = $group->users;
        $notmembers = \App\User::whereNotIn('id', $members->pluck('id'))->verified()->orderBy('name')->pluck('name', 'id');

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
    public function store(Request $request, Group $group)
    {
        $this->authorize('manage-membership', $group);

        if ($request->has('users')) {
            foreach ($request->get('users') as $user_id) {
                $user = \App\User::findOrFail($user_id);
                // load or create membership for this group and user combination
                $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $group->id]);
                $membership->membership = \App\Membership::MEMBER;

                // we prented the user has been already notified once, now. The first mail sent will be at the choosen interval from now on.
                $membership->notified_at = Carbon::now();
                $membership->save();

                // notify the user
                $user->notify(new \App\Notifications\AddedToGroup($group));

                flash(trans('messages.user_added_successfuly').' : '.$user->name);
            }
        }

        // TODO notifiy user

        return redirect()->route('groups.users.index', $group);
    }
}
