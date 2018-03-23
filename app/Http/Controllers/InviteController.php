<?php

namespace App\Http\Controllers;

use App\Group;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

use App\Mail\InviteUser;

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
        return view('invites.form')
        ->with('tab', 'users')
        ->with('group', $group);
    }

    /**
    * Send invites to new members by email.
    *
    * @param int $group_id [description]
    *
    * @return [type] [description]
    */
    public function sendInvites(Request $request, Group $group)
    {
        $status_message = null;

        // extract emails
        // from http://textsnippets.com/posts/show/179
        preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $request->invitations, $matches);
        $emails = $matches[0];
        $emails = array_unique($emails);

        // for each invite email,
        foreach ($emails as $email) {

            // - check that the user has not been invited yet for this group
            $invitation_counter = \App\Invite::where('email', '=', $email)
            ->where('claimed_at', '=', null)
            ->where('group_id', '=', $group->id)
            ->count();

            // check that the user is not already member of the group
            $user_already_member = false;
            $user = \App\User::where('email', $email)->first();
            if ($user) {
                if ($user->isMemberOf($group)) {
                    $user_already_member = true;
                }
            }

            /*
            if group is restricted (private), we proceed diferently :
            - if user is already registered we add him/her immediately
            - if user is not registered yet, as soon as (s)he registers, (s)he is added to the groups (this case is handled in the auth controller)
            */


            if (!$group->isOpen()) {
                if ($user) {
                    // add user to membership for the group taken from the invite table
                    $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $group->id]);
                    $membership->membership = \App\Membership::MEMBER;
                    $membership->save();
                    $status_message .= trans('membership.users_has_been_added').' : '.$email.'<br/>';
                }
            }

            if ($invitation_counter > 0 || $user_already_member) {
                $status_message .= trans('membership.user_already_invited').' : '.$email.'<br/>';
            } else {
                // - create an invite token and store in invite table
                $invite = new \App\Invite;
                $invite->generateToken();
                $invite->email = $email;
                $invite->group()->associate($group);
                $invite->user()->associate(Auth::user());

                if ($invite->save())
                {
                    // - send invitation email
                    Mail::to($email)->send(new InviteUser($invite));
                    $status_message .= trans('membership.users_has_been_invited').' : '.$email.'<br/>';
                }
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
            flash(trans('messages.invite_not_found'))->error();

            return redirect()->route('groups.show', $group->id);
        }

        if (isset($invite->claimed_at)) {
            flash(trans('messages.invite_already_used').' ('.$invite->claimed_at.')')->warning();

            return redirect()->route('groups.show', $group->id);
        }

        $user = \App\User::where('email', $invite->email)->first();

        // check if user exists
        if ($user) {
            // add user to membership for the group taken from the invite table
            $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $invite->group_id]);
            $membership->membership = \App\Membership::MEMBER;
            $membership->notification_interval = 60 * 24 * 7; // this is a sane default imho for notification interval (weekly)
            $membership->save();

            // Invitation is now claimed, but not deleted
            $invite->claimed_at = Carbon::now();

            $invite->save();

            flash(trans('messages.you_are_now_a_member_of_this_group'))->success();

            return redirect()->route('groups.show', $group->id);
        } else { // if user doesn't exists, we have the opportunity to create, login and validate email in one go (since we have the invite token)
            Auth::logout();
            flash(trans('messages.you_dont_have_an_account_create_one_now'));

            return view('invites.register')
            ->with('email', $invite->email)
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

        $invite = \App\Invite::whereToken($token)->firstOrFail();
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

        flash(trans('messages.you_are_now_a_member_of_this_group'))->success();

        return redirect()->route('groups.show', $group->id);
    }
}
