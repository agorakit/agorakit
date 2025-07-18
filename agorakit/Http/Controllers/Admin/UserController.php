<?php

namespace Agorakit\Http\Controllers\Admin;

use Agorakit\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$users = \Agorakit\User::where('verified', 1)->get();
        $users = \Agorakit\User::get();

        return view('admin.user.index')->with('users', $users);
    }
}
