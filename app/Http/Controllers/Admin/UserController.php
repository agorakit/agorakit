<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$users = \App\Models\User::where('verified', 1)->get();
        $users = \App\Models\User::get();

        return view('admin.user.index')->with('users', $users);
    }
}
