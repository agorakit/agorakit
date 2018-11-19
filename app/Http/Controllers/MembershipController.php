<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use App\Membership;
use App\Invite;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
  public function __construct()
  {
    $this->middleware('verified');
  }


  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index(Group $group)
  {
    $memberships = $group->memberships()->with('user')->orderBy('membership', 'desc')->get();

    $admins = $group->admins()->orderBy('name')->get();
    $candidates = $group->candidates()->orderBy('name')->get();
    $invites = Invite::where('group_id', $group->id)->whereNull('claimed_at')->get();




    return view('users.index')
    ->with('memberships', $memberships)
    ->with('admins', $admins)
    ->with('candidates', $candidates)
    ->with('invites', $invites)
    ->with('group', $group)
    ->with('tab', 'users');
  }


  /**
  * Show a form to allow a user to join a group
  */
  public function create(Request $request, Group $group)
  {
    if ($group->isOpen()) {
      // load or create membership for this group and user combination
      $membership = Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group->id]);

      return view('membership.join')
      ->with('group', $group)
      ->with('tab', 'settings')
      ->with('membership', $membership)
      ->with('interval', 'daily');
    } else {
      return view('membership.apply')
      ->with('group', $group)
      ->with('tab', 'settings');
    }
  }

  /**
  * Store the membership. It means: add a user to a group and store his/her notification settings.
  *
  * @param Request $request  [description]
  * @param [type]  $group_id [description]
  *
  * @return [type] [description]
  */
  public function store(Request $request, Group $group)
  {
    if ($group->isOpen()) {
      // load or create membership for this group and user combination
      $membership = Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group->id]);
      $membership->membership = Membership::MEMBER;
      $membership->notification_interval = intervalToMinutes($request->get('notifications'));

      // we prented the user has been already notified once, now. The first mail sent will be at the choosen interval from now on.
      $membership->notified_at = Carbon::now();
      $membership->save();

      return redirect()->route('groups.show', [$group->id])->with('message', trans('membership.welcome'));
    } else {
      // load or create membership for this group and user combination
      $membership = Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group->id]);
      $membership->membership = Membership::CANDIDATE;
      $membership->notification_interval = 60*24;

      // we prented the user has been already notified once, now. The first mail sent will be at the choosen interval from now on.
      $membership->notified_at = Carbon::now();
      $membership->save();

      // notify group admins
      foreach ($group->admins as $admin) {
        $admin->notify(new \App\Notifications\AppliedToGroup($group, $request->user()));
      }

      return redirect()->route('groups.show', $group)->with('message', trans('membership.application_stored'));
    }
  }

  /**
  * Show a settings screen for a specific group. Allows a user to leave the group.
  */
  public function destroyConfirm(Request $request, Group $group)
  {
    // load a membership for this group and user combination
    $membership =Membership::where(['user_id' => $request->user()->id, 'group_id' => $group->id])->firstOrFail();

    return view('membership.leave')
    ->with('group', $group)
    ->with('tab', 'settings')
    ->with('membership', $membership);
  }

  /**
  * Remove the specified user from the group.
  *
  * @param int $id
  *
  * @return Response
  */
  public function destroy(Request $request, Group $group)
  {
    // load or create membership for this group and user combination
    $membership = Membership::where(['user_id' => $request->user()->id, 'group_id' => $group->id])->firstOrFail();
    $membership->membership = Membership::UNREGISTERED;
    $membership->save();

    return redirect()->action('DashboardController@index');
  }

  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings.
  */
  public function edit(Request $request, Group $group, Membership $membership = null)
  {
    // load or create membership for this group and user combination
    if ($membership) // we have a membership we need to authorize the edit
    {
      $this->authorize('edit-membership', $group);
    }
    else // we edit membership for the current user
    {
      $membership = Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group->id]);
    }


    return view('membership.edit')
    ->with('tab', 'preferences')
    ->with('group', $group)
    ->with('interval', minutesToInterval($membership->notification_interval))
    ->with('membership', $membership);
  }

  /**
  * Store new settings from the preferencesForm.
  */
  public function update(Request $request, Group $group,  Membership $membership = null)
  {
    // load membership for this group and the current user combination
    if (!$membership)
    {
      $membership = \App\Membership::firstOrFail(['user_id' => $request->user()->id, 'group_id' => $group->id]);
    }

    // authorize editing
    $this->authorize('edit', $membership);

    // if a membership level is defined, we need to check if the user is admin of the group to allow editing of membership levels
    if ($request->get('membership_level'))
    {
      $this->authorize('edit-membership', $group);
      $membership->membership = $request->get('membership_level');
    }


    $membership->notification_interval = intervalToMinutes($request->get('notifications'));
    $membership->save();

    return redirect()->route('groups.users.index', $group)->with('message', trans('membership.settings_updated'));
  }

  /**
  * Show an explanation page on how to join a private group.
  */
  public function howToJoin(Request $request, Group $group)
  {
    return view('membership.howtojoin')
    ->with('tab', 'preferences')
    ->with('group', $group);
  }


}
