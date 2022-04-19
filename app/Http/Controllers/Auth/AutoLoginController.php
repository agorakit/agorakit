<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;

class AutoLoginController extends Controller
{
    public function login($username, Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature');
        }
        $user = User::where('username', $username)->firstOrFail();
        Auth::login($user, true);

        $user->verified = 1;
        $user->save();

        if ($request->get('redirect')) {
            return redirect($request->get('redirect'));
        }

        return redirect('/');
    }
}
