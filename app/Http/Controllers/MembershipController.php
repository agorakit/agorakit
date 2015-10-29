<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Mail;

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
  public function join(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.join')
    ->with('group', $group)
    ->with('membership', $membership);
  }

  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings.
  */
  public function leave(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.leave')
    ->with('group', $group)
    ->with('membership', $membership);
  }

  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings.
  */
  public function settings(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.edit')
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
  public function store(Request $request, $group_id)
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

    return redirect()->action('GroupController@show', [$group->id])->with('message', 'Welcome to this group');
  }

  /**
  * Shows an invitation form for the specific group.
  *
  * @param  [type] $group_id [description]
  *
  * @return [type]           [description]
  */
  public function invite(Request $request, $group_id)
  {
    // TODO : only confirmed users should be able to mass invite
    // Explain that on the form
    $group = \App\Group::findOrFail($group_id);

    return view('membership.invite')
    ->with('group', $group);
  }

  /**
  * Send invites to new members by email.
  *
  * @param  int $group_id [description]
  *
  * @return [type]           [description]
  */
  public function sendInvites(Request $request, $group_id)
  {
    // extract emails
    // from http://textsnippets.com/posts/show/179
    preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $request->invitations, $matches);
    $emails = $matches[0];
    $emails = array_unique($emails);
    //dd($emails);

    // If it's a mass invite, only confirmed people can do that

    // for each invite email,
    foreach ($emails as $email) {
      // - check that the user has not been invited yet for this group
      $invitation_counter = \App\Invite::where('email', '=', $email)
      ->where('claimed_at', '=', null)
      ->where('group_id', '=', $group_id)
      ->count();

      if ($invitation_counter > 0) {
        $messages[] = trans('membership.user_already_invited') . ' (' . $email . ')';
      }
      else
      {
        // - create an invite token and store in invite table
        $invite = new \App\Invite;
        $invite->generateToken();
        $invite->email = $email;

        $group = \App\Group::findOrFail($group_id);
        $invite->group_id = $group->id;
        $invite->user_id = $request->user()->id;
        $invite->save();
        // - send invitation email


        Mail::send('emails.invite', ['invite' => $invite, 'group' => $group, 'invitating_user' => $request->user()], function ($message) use ($email) {
          $message->from('noreply@example.com', 'Laravel');
          $message->to($email);
        });
      }
    }

    // - queue or wathever if more than 50 mails for instance
    //
    // show success screen
    //

  }

  /**
  * Update the specified resource in storage.
  *
  * @param  int  $id
  *
  * @return Response
  */
  public function update($id)
  {
    /* TODO
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::findOrFail(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    $membership->membership = 2; // TODO
    $membership->save();
    return redirect()->action('GroupController@show', [$group->id])->with('message', 'Your settings have been updated');
    */
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  *
  * @return Response
  */
  public function destroy(Request $request, $group_id)
  {
    /* TODO or remove
    // load or create membership for this group and user combination
    $membership = \App\Membership::where(['user_id' => $request->user()->id, 'group_id' => $group_id])->firstOrFail();
    $membership->membership = -1;
    $membership->save();
    return redirect()->action('GroupController@index');
    */
  }
}
