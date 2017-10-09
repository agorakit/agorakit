<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified', ['only' => ['settings', 'update']]);
    }

    /**
     * Display a settings edition screen. Currently only the homepage intro text, but this will change soon :-)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
