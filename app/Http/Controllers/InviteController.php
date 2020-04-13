<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use App\Mail\InviteUser;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware('member', ['only' => ['invite', 'sendInvites']]);
        $this->middleware('verified', ['only' => ['invite', 'sendInvites']]);
        $this->middleware('throttle:2,1', ['only' => ['sendInvites']]); // 2 emails per  minute should be enough for non bots
    }

    /**
    * Shows an invitation form for the specific group.
    *
    * @param [type] $group_id [description]
    *
    * @return [type] [description]
    */
    public function invite(Request $request, Group $group)
    {
        $this->authorize('invite', $group);

        return view('invites.form')
        ->with('tab', 'users')
        ->with('group', $group);
    }

    /**
    * Send invites to new members by email.
    * Create user accounts already for those users.
    * Set membership status to "invited"
    *
    * @param int $group_id [description]
    *
    * @return [type] [description]
    */
    public function sendInvites(Request $request, Group $group)
    {
        $this->authorize('invite', $group);

        $group_user = Auth::user();

        $status_message = null;

        // extract emails
        // from http://textsnippets.com/posts/show/179
        preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $request->invitations, $matches);
        $emails = $matches[0];
        $emails = array_unique($emails);

        // for each invite email,
        foreach ($emails as $email) {
            // find or create users
            $user = User::firstOrCreate(['email' => $email]);

            if ($user->isMemberOf($group)) {
                $status_message .= trans('membership.user_already_invited').' : '.$email.'<br/>';
            }
            else
            {
                $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $group->id]);
                $membership->membership = \App\Membership::INVITED;
                $membership->save();

                // send invitation email
                Mail::to($email)->send(new InviteUser($group_user, $membership));
                $status_message .= trans('membership.users_has_been_invited').' : '.$email.'<br/>';


            }
        }


        // NICETOHAVE We could queue or wathever if more than 50 mails for instance.
        // But it's also a kind of spam prevention that it takes time to invite on the server

        if ($status_message) {
            flash($status_message);
        }

        return redirect()->back();
    }

    /**
    * Whenever a user wants to confirm an invite he received from an email link
    * - if user exists we directly subscribe him/her to the group
    * - if not we show an account creation screen.
    */
    public function inviteConfirm(Request $request, Group $group, $token)
    {
        $invite = \App\Invite::whereToken($token)->first();

        if (!$invite) {
            flash(trans('messages.invite_not_found'));

            return redirect()->route('groups.show', $group);
        }

        if (isset($invite->claimed_at)) {
            flash(trans('messages.invite_already_used').' ('.$invite->claimed_at.')');

            return redirect()->route('groups.show', $group);
        }

        $user = \App\User::where('email', $invite->email)->first();

        // check if user exists
        if ($user) {
            // add user to membership for the group taken from the invite table
            $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $invite->group_id]);
            $membership->membership = \App\Membership::MEMBER;
            $membership->notification_interval = 60 * 24; // this is a sane default imho for notification interval (daily)
            $membership->save();

            // Invitation is now claimed, but not deleted
            $invite->claimed_at = Carbon::now();

            $invite->save();

            flash(trans('messages.you_are_now_a_member_of_this_group'));

            return redirect()->route('groups.show', $group);
        } else { // if user doesn't exists, we have the opportunity to create, login and validate email in one go (since we have the invite token)
            Auth::logout();
            flash(trans('messages.you_dont_have_an_account_create_one_now'));

            return view('auth.register')
            ->with('email', $invite->email)
            ->with('invite_and_register', true)
            ->with('group', $group)
            ->with('token', $token);
        }
    }

    /**
    * Process the account creation from the form of inviteConfirm().
    */
    public function inviteRegister(Request $request, Group $group, $token)
    {
        $this->validate($request, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $invite = \App\Invite::whereToken($token)->firstOrFail(); // TODO show a nicer error message if not found
        $invite->claimed_at = Carbon::now();
        $invite->save();

        $user = new \App\User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));

        // in the strange event the user changes the email on the registration form, we cannot consider it is verified using the invite.
        if ($invite->email == $request->get('email')) {
            $user->verified = 1;
        }

        $user->save();

        $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $group->id]);
        $membership->membership = \App\Membership::MEMBER;
        $membership->notification_interval = 60 * 24 * 7; // this is a sane default imho for notification interval (weekly)
        $membership->save();

        Auth::login($user);

        flash(trans('messages.you_are_now_a_member_of_this_group'));

        return redirect()->route('groups.show', $group);
    }
}
