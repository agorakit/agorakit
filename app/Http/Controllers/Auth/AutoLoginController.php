<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\User;
use Auth;

class AutoLoginController extends Controller
{
    public function login($username, Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature');
        }
        $user = User::where('username', $username)->first();
        Auth::login($user, true);

        if ($request->get('redirect')) {
            return redirect($request->get('redirect'));
        }
    }

}
