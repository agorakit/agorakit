<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Image;
use Storage;
use App\Setting;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a settings edition screen. Currently only the homepage intro text, but this will change soon :-).
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
        if (Auth::user()->isAdmin()) {
            Setting::set('name', $request->get('name'));
            Setting::set('homepage_presentation', $request->get('homepage_presentation'));
            Setting::set('homepage_presentation_for_members', $request->get('homepage_presentation_for_members'));
            Setting::set('help_text', $request->get('help_text'));
            Setting::set('user_can_create_groups', $request->has('user_can_create_groups') ? 1 : 0);
            Setting::set('user_can_create_secret_groups', $request->has('user_can_create_secret_groups') ? 1 : 0);
            Setting::set('notify_admins_on_group_create', $request->has('notify_admins_on_group_create') ? 1 : 0);

            Setting::set('custom_footer', $request->get('custom_footer'));

            // handle app logo
            if ($request->hasFile('logo')) {
                Storage::makeDirectory('public/logo');
                Image::make($request->file('logo'))->fit(128, 128)->save(storage_path().'/app/public/logo/favicon.png');
                Image::make($request->file('logo'))->fit(640, 640)->save(storage_path().'/app/public/logo/logo.jpg');
            }

            flash('Settings saved');

            return view('admin.settings.index');
        } else {
            flash(trans('messages.not_allowed'));

            return redirect()->action('DashboardController@index');
        }
    }
}
