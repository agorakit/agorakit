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
        // We need to bypass the Illuminate\Database\Eloquent\SoftDeletingScope
        $users = \App\User::withTrashed()->get();

        return view('admin.user.index')->with('users', $users);
    }
}
