<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;


/**
 * To be honest this one is a candidate for supresion of refactor. Activity feed is not that useful in the current application.
 * And I'm wondering if it's not just another trick used by big players to keep people attracted to their app. We don't want tricks.
 */

class ActivityController extends Controller
{
  public function __construct()
  {
    $this->middleware('verified');
    $this->middleware('preferences');
  }



  public function index()
  {
    $activities = \App\Activity::with('user', 'group', 'model')->orderBy('created_at', 'desc')->paginate(50);

    return view('dashboard.activities')
    ->with('tab', 'homepage')
    ->with('activities', $activities);
  }



}
