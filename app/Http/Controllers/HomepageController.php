<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Group;
use Auth;


class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $groups = Group::all();

      if (Auth::user())
      {
           $mygroups = Auth::user()->groups();
      }
      else
      {
        $mygroups = false;
      }

      return view('groups.index')->with('groups', $groups)->with('mygroups', $mygroups);
    }


}
