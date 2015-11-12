<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Auth;


class InviteController extends Controller
{

  public function __construct()
  {
    $this->middleware('member', ['only' => ['invite', 'sendInvites']]);
    $this->middleware('verified', ['only' => ['invite', 'sendInvites']]);
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
    // Explain that on the form
    $group = \App\Group::findOrFail($group_id);

    return view('invites.form')
    ->with('tab', 'users')
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

    $status_message = null;

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
        $status_message .= trans('membership.user_already_invited').' : ' . $email .'<br/>';
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
          $message->from(env('MAIL_FROM', 'noreply@example.com'), env('APP_NAME', 'Laravel'))
          ->to($email)
          ->subject( '[' . env('APP_NAME') . '] Invitation à rejoindre le groupe "' . $group->name . '"');
        });


        $status_message .= trans('membership.users_has_been_invited') .  ' : ' .  $email . '<br/>';
      }
    }
    // NICETOHAVE We could queue or wathever if more than 50 mails for instance.
    // But it's also a kind of spam prevention that it takes time to invite on the server


    if ($status_message)
    {
      $request->session()->flash('message', $status_message );
    }
    return redirect()->back();

  }


  /**
   * Whenever a user wants to confirm an invite he received from an email link
   * - if user exists we directly subscribe him/her to the group
   * - if not we show an account creation screen
   */
  public function inviteConfirm(Request $request, $group_id, $token)
  {
    // check if token is valid
    $invite = \App\Invite::whereToken($token)->firstOrFail();

    $user = \App\User::where('email', $invite->email)->first();
    $group = \App\Group::findOrFail($invite->group_id);

    // check if user exists
    if ($user)
    {
      // add user to membership for the group taken from the invite table
      $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $invite->group_id]);
      $membership->membership = 20;
      $membership->save();

      // remove invite we don't need it anymore, or do we for logging purposes?
      $invite->delete();

      $request->session()->flash('message', 'Vous êtes maintenant membre de ce groupe' );
      return redirect()->action('GroupController@show', $group_id);
    }
    else
    {
      // if user doesn't exists, we have the opportunity to create, login and validate email in one go (since we have the invite token)
      $request->session()->flash('message', 'Vous n\'avez pas encore de compte sur ce site, merci de vous en créer un');

      return view('invites.register')
      ->with('email', $invite->email)
      ->with('group', $group)
      ->with('token', $token);
    }
  }


  /**
   * Process the account creation from the form of inviteConfirm()
   */
  public function inviteRegister(Request $request, $group_id, $token)
  {
    $this->validate($request, [
      'name' => 'required|max:255',
      'email' => 'required|email|max:255|unique:users',
      'password' => 'required|confirmed|min:6',
      ]);

      $invite = \App\Invite::whereToken($token)->firstOrFail();
      $invite->delete();

      $user = new \App\User;
      $user->name = $request->get('name');
      $user->email = $request->get('email');
      $user->password = bcrypt($request->get('password'));

      // in the strange event the user changes the email on the registration form, we cannot consider it is verified using the invite.
      if ($invite->email == $request->get('email'))
      {
        $user->verified = 1;
      }

      $user->save();

      $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $group_id]);
      $membership->membership = 20;
      $membership->save();

      Auth::login($user);

      $request->session()->flash('message', 'Vous êtes maintenant membre de ce groupe' );
      return redirect()->action('GroupController@show', $group_id);
    }
  }
