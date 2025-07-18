<?php

namespace Agorakit\Http\Controllers\Auth;

use Agorakit\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Agorakit\User;
use Auth;

class AutoLoginController extends Controller
{
    public function login(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature');
        }
        $user = User::where('username', $request->get('username'))->firstOrFail();
        Auth::login($user, true);

        $user->verified = 1;
        $user->save();

        return redirect('/');
    }
}
