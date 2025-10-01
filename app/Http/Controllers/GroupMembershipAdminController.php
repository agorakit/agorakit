<?php

namespace App\Http\Controllers;

use App\Group;
use App\Invite;
use App\Membership;
use App\User;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

class GroupMembershipAdminController extends Controller
{
    /**
    * Show admin a form to edit a membership
    */
    public function edit(Request $request, Group $group, Membership $membership)
    {
        $this->authorize('edit', $membership);

        return view('membership.admin.edit')
        ->with('title', $group->name . ' - ' . trans('messages.settings'))
        ->with('tab', 'users')
        ->with('group', $group)
        ->with('interval', minutesToInterval($membership->notification_interval))
        ->with('membership', $membership);
    }

    /**
    * Store new settings from the membership form
    */
    public function update(Request $request, Group $group, Membership $membership)
    {
        // authorize editing
        $this->authorize('edit', $membership);

        // if a membership level is defined, we need to check if the user is admin of the group to allow editing of membership levels
        if ($request->has('membership_level')) {
            $this->authorize('manage-membership', $group);

            // handle the case an admin change his own level and is the only one admin of the group... yes it hapened...
            if ($membership->isAdmin() && $group->admins->count() == 1 && $request->get('membership_level') < \App\Membership::ADMIN) {
                flash('You cannot remove you as admin since you are the unique admin. Promote someone else as admin first.');

                return redirect()->back();
            }

            $membership->membership = $request->get('membership_level');
        }

        $membership->notification_interval = intervalToMinutes($request->get('notifications'));
        $membership->save();

        flash(trans('membership.settings_updated'));
        return redirect()->route('groups.users.index', $group);
    }
}
