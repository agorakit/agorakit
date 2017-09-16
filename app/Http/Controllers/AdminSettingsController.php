<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified', ['only' => ['settings', 'update']]);
    }

    /**
     * Display a settings edition screen.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('settings.list')
        ->with('homepage_presentation', \App\Setting::get('homepage_presentation'));
    }

    /**
     * Update settings from the edit form.
     */
    public function update(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            \App\Setting::set('homepage_presentation', $request->input('homepage_presentation'));

            return redirect()->action('DashboardController@index');
        } else {
            flash(trans('messages.not_allowed'))->error();

            return redirect()->action('DashboardController@index');
        }
    }
}
