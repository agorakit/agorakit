<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use App\Membership;
use App\Mail\InviteUser;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

class InviteController extends Controller
{
    public function __construct()
    {
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
                $status_message .= trans('membership.user_already_invited') . ' : ' . $email . '<br/>';
            } else {
                $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $group->id]);
                $membership->membership = \App\Membership::INVITED;
                $membership->save();

                // send invitation email
                Mail::to($email)->send(new InviteUser($group_user, $membership));
                $status_message .= trans('membership.users_has_been_invited') . ' : ' . $email . '<br/>';
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
    * Show a list of invites for the current user  and allow to accept / discard the invite
    */
    public function index(Request $request)
    {
        $memberships = Auth::user()->memberships()->where('membership', Membership::INVITED)->get();
        return view('membership.invites')
        ->with('memberships', $memberships);
    }

    public function accept(Request $request, Membership $membership)
    {
        if (Auth::user()->id == $membership->user_id) {
            $membership->membership = Membership::MEMBER;
            $membership->save();
            return redirect()->route('invites.index');
        }
    }

    public function deny(Request $request, Membership $membership)
    {
        if (Auth::user()->id == $membership->user_id) {
            $membership->membership = Membership::DECLINED;
            $membership->save();
            return redirect()->route('invites.index');
        }
    }

    /**
     * Allow user to accept an invitation directly from a signed url sent by email
     */
    public function acceptWithSignature(Request $request, Membership $membership)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature');
        }

        $user = $membership->user;
        Auth::login($user, true);

        $user->verified = 1;
        $user->save();

        $membership->membership = Membership::MEMBER;
        $membership->save();

        flash(trans('membership.welcome'));

        return redirect()->route('groups.show', $membership->group);
    }

    /**
     * Allow user to deny an invitation directly from a signed url sent by email
     */
    public function denyWithSignature(Request $request, Membership $membership)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature');
        }

        $user = $membership->user;
        //Auth::login($user, true); // login not needed, maybe the user really does not want to have anything to do with us

        $user->verified = 1;
        $user->save();

        $membership->membership = Membership::DECLINED;
        $membership->save();

        flash(trans('membership.refusal_recorded'));

        return redirect()->route('groups.show', $membership->group);
    }
}
