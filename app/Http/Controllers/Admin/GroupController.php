<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Group;

class GroupController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {

        $groups = Group::withCount('users')
        ->withCount('discussions')
        ->get();

        //dd($groups);

        return view('admin.group.index')->with('groups', $groups);
    }
}
