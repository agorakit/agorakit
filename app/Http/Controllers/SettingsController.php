<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified', ['only' => ['settings', 'update']]);
    }


    /**
    * Display a settings edition screen
    *
    * @return \Illuminate\Http\Response
    */
    public function settings()
    {
        return view('settings.list')
        ->with('homepage_presentation', \App\Setting::get('homepage_presentation'));

    }


    /**
    * Update settings from the edit form
    */
    public function update(Request $request)
    {
        if (Auth::user()->isAdmin())
        {
            \App\Setting::set('homepage_presentation', $request->input('homepage_presentation'));
            return redirect()->action('DashboardController@index');
        }
        else
        {
            flash()->error(trans('messages.not_allowed'));
            return redirect()->action('DashboardController@index');
        }

    }


}
