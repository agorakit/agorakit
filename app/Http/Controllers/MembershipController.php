<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Carbon\Carbon;

class MembershipController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth', ['only' => ['join', 'joinForm', 'leave', 'leaveForm', 'settings', 'settingsForm']]);
  }

  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings.
  */
  public function joinForm(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.join')
    ->with('group', $group)
    ->with('tab', 'settings')
    ->with('membership', $membership)
    ->with('interval', 'daily');
    ;
  }

  /**
  * Store the membership. It means: add a user to a group and store his/her notification settings.
  *
  * @param  Request $request  [description]
  * @param  [type]  $group_id [description]
  *
  * @return [type]            [description]
  */
  public function join(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    $membership->membership = 20;

    $membership->notification_interval = $this->intervalToMinutes($request->get('notifications'));

    // we prented the user has been already notified once, now. The first mail sent will be at the choosen interval from now on.
    $membership->notified_at = Carbon::now();

    $membership->save();

    return redirect()->action('GroupController@show', [$group->id])->with('message', trans('membership.welcome'));
  }


  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings.
  */
  public function leaveForm(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.leave')
    ->with('group', $group)
    ->with('tab', 'settings')
    ->with('membership', $membership);
  }


  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  *
  * @return Response
  */
  public function leave(Request $request, $group_id)
  {

    // load or create membership for this group and user combination
    $membership = \App\Membership::where(['user_id' => $request->user()->id, 'group_id' => $group_id])->firstOrFail();
    $membership->membership = -10;
    $membership->save();
    return redirect()->action('GroupController@index');

  }


  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings.
  */
  public function settingsForm(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.edit')
    ->with('tab', 'settings')
    ->with('group', $group)
    ->with('interval', $this->minutesToInterval($membership->notification_interval))
    ->with('membership', $membership);
  }


  /**
   * Store new settings from the settingsForm
   */
  public function settings(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    $membership->membership = 20;

    $membership->notification_interval = $this->intervalToMinutes($request->get('notifications'));

    $membership->save();

    return redirect()->action('GroupController@show', [$group->id])->with('message', trans('membership.settings_updated'));

  }


  function intervalToMinutes($interval)
  {
    $minutes = -1;

    switch ($interval) {
      case 'hourly':
      $minutes = 60;
      break;
      case 'daily':
      $minutes = 60 * 24;
      break;
      case 'weekly':
      $minutes = 60 * 24 * 7;
      break;
      case 'biweekly':
      $minutes = 60 * 24 * 14;
      break;
      case 'monthly':
      $minutes = 60 * 24 * 30;
      break;
      case 'never':
      $minutes = -1;
      break;
    }
    return $minutes;
  }

  function minutesToInterval($minutes)
  {

    $interval = 'never';

    switch ($minutes) {
      case 60:
      $interval = 'hourly';
      break;
      case 60 * 24:
      $interval = 'daily';
      break;
      case 60 * 24 * 7:
      $interval = 'weekly';
      break;
      case 60 * 24 * 14:
      $interval = 'biweekly';
      break;
      case 60 * 24 * 30:
      $interval = 'monthly';
      break;
      case -1:
      $interval = 'never';
      break;
    }

    return $interval;

  }






}
