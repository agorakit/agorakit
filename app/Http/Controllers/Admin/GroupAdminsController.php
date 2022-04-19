<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;

class GroupAdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $memberships = Membership::where('membership', Membership::ADMIN)
        ->with('user')
        ->get();

        $users = collect();

        foreach ($memberships as $membership) {
            $users->push($membership->user);
        }

        $users = $users->unique('username');

        return view('admin.groupadmins.index')->with('users', $users);
    }
}
