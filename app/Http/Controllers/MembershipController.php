<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;


class MembershipController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth', ['only' => ['join', 'leave', 'settings', 'store', 'edit', 'update', 'destroy']]);
    $this->middleware('member', ['only' => ['invite', 'sendInvites']]);
  }

  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings.
  */
  public function joinConfirm(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.join')
    ->with('group', $group)
    ->with('membership', $membership);
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

    //dd($request->all());

    switch ($request->get('notifications')) {
      case 'hourly':
      $membership->notifications = 60;
      break;
      case 'daily':
      $membership->notifications = 60 * 24;
      break;
      case 'weekly':
      $membership->notifications = 60 * 24 * 7;
      break;
      case 'biweekly':
      $membership->notifications = 60 * 24 * 14;
      break;
      case 'monthly':
      $membership->notifications = 60 * 24 * 30;
      break;
      case 'never':
      $membership->notifications = -1;
      break;
    }

    $membership->save();

    return redirect()->action('GroupController@show', [$group->id])->with('message', trans('membership.welcome'));
  }


  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings.
  */
  public function leaveConfirm(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.leave')
    ->with('group', $group)
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
    ->with('group', $group)
    ->with('membership', $membership);
  }


  /**
   * Store new settings from the settingsForm
   */
  public function settings(Request $request, $group_id)
  {
    // TODO
  }






}
