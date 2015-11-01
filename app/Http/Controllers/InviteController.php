<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class InviteController extends Controller
{

  public function __construct()
  {
    $this->middleware('member', ['only' => ['invite', 'sendInvites']]);
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

    return view('invites.form')
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
    $status_messages = []; // this will store all the status info (who has been invited for example) returned tot he user


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
        $status_messages[] = trans('membership.user_already_invited').' ('.$email.')';
      } else {
        // - create an invite token and store in invite table
        $invite = new \App\Invite();
        $invite->generateToken();
        $invite->email = $email;

        $group = \App\Group::findOrFail($group_id);
        $invite->group_id = $group->id;
        $invite->user_id = $request->user()->id;
        $invite->save();
        // - send invitation email


        Mail::send('emails.invite', ['invite' => $invite, 'group' => $group, 'invitating_user' => $request->user()], function ($message) use ($email, $request, $group) {
          $message->from('noreply@example.com', 'Laravel');
          $message->to($email);
          $message->subject($request->user()->name . ' vous invite à ' . $group->name);
        });


        $status_messages[] = $email . ' '. trans('membership.users_has_been_invited');
      }
    }
    // TODO queue or wathever if more than 50 mails for instance. But it's also a kind of spam prevention that it takes time to invite on the server

    // show success screen
    return $status_messages;
  }


  public function inviteConfirm(Request $request, $group_id, $token)
  {
    // check if token is valid
    // add user to membership for the group taken from the invite table

  }

}
