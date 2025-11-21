<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;

class AutoLoginController extends Controller
{
    public function login(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired signature');
        }
        $user = User::where('username', $request->get('username'))->firstOrFail();

        // discard banned user
        if ($user->isBanned()) {
            return false;    
        }

        Auth::login($user, true);
        $user->verified = 1;
        $user->save();

        return redirect('/');
    }
}
