<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
    * Display a settings edition screen. Currently only the homepage intro text, but this will change soon :-)
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
    * Update settings from the edit form.
    */
    public function update(Request $request)
    {
        if (Auth::user()->isAdmin())
        {
            \App\Setting::set('homepage_presentation', $request->get('homepage_presentation'));
            \App\Setting::set('homepage_presentation_for_members', $request->get('homepage_presentation_for_members'));
            \App\Setting::set('help_text', $request->get('help_text'));
            \App\Setting::set('user_can_create_groups', $request->has('user_can_create_groups') ? 1 : 0);
            \App\Setting::set('notify_admins_on_group_create', $request->has('notify_admins_on_group_create') ? 1 : 0);

            flash('Settings saved')->success();
            return view('admin.settings.index');
        }
        else
        {
            flash(trans('messages.not_allowed'))->error();
            return redirect()->action('DashboardController@index');
        }
    }
}
