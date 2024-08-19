<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Image;
use Storage;
use Illuminate\Support\Str;
use App\Setting;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $overviewItems = [
            "show_overview_all_groups" => trans('messages.all_groups'),
            "show_overview_discussions" => trans('messages.discussions'),
            "show_overview_agenda" => trans('messages.agenda'),
            "show_overview_tags" => trans('messages.tags'),
            "show_overview_map" => trans('messages.map'),
            "show_overview_files" => trans('messages.files'),
            "show_overview_users" => trans('messages.users'),
        ];

        return view(
            'admin.settings.index',
            compact('overviewItems')
        );
    }

    /**
     * Update settings from the edit form.
     */
    public function update(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            flash(trans('messages.not_allowed'));
            return redirect()->action('DashboardController@index');
        }

        setting()->set('name', $request->get('name'));

        if ($request->has('homepage_presentation')) {
            foreach ($request->get('homepage_presentation') as $locale => $value) {
                setting()->localized($locale)->set('homepage_presentation', $value);
            }
        }
        if ($request->has('homepage_presentation_for_members')) {
            foreach ($request->get('homepage_presentation_for_members') as $locale => $value) {
                setting()->localized($locale)->set('homepage_presentation_for_members', $value);
            }
        }

        if ($request->has('help_text')) {
            foreach ($request->get('help_text') as $locale => $value) {
                setting()->localized($locale)->set('help_text', $value);
            }
        }

        setting()->set('user_can_create_groups', $request->has('user_can_create_groups') ? 1 : 0);
        setting()->set('user_can_create_secret_groups', $request->has('user_can_create_secret_groups') ? 1 : 0);
        setting()->set('notify_admins_on_group_create', $request->has('notify_admins_on_group_create') ? 1 : 0);
        setting()->set('user_can_register', $request->has('user_can_register') ? 1 : 0);

        setting()->setArray('user_tags', $request->get('user_tags'));
        setting()->setArray('group_tags', $request->get('group_tags'));


        setting()->set('show_overview_inside_navbar', $request->has('show_overview_inside_navbar') ? 1 : 0);
        setting()->set('show_help_inside_navbar', $request->has('show_help_inside_navbar') ? 1 : 0);

        $overviewItems = [
            "show_overview_all_groups" => trans('messages.all_groups'),
            "show_overview_discussions" => trans('messages.discussions'),
            "show_overview_agenda" => trans('messages.agenda'),
            "show_overview_tags" => trans('messages.tags'),
            "show_overview_map" => trans('messages.map'),
            "show_overview_files" => trans('messages.files'),
            "show_overview_users" => trans('messages.users'),
        ];

        foreach (array_keys($overviewItems) as $item) {
            setting()->set($item, $request->has($item) ? 1 : 0);
        }

        setting()->set('show_locales_inside_navbar', $request->has('show_locales_inside_navbar') ? 1 : 0);
        foreach (config('app.locales') as $locale) {
            setting()->set("show_locale_{$locale}", $request->has("show_locale_{$locale}") ? 1 : 0);
        }

        setting()->set('custom_footer', $request->get('custom_footer'));

            // handle app logo
            if ($request->hasFile('logo')) {
                Storage::makeDirectory('public/logo');
                Image::make($request->file('logo'))->widen(1024)->save(storage_path() . '/app/logo.png');
            }

        flash('Settings saved');

        return view('admin.settings.index', compact('overviewItems'));
    }
}
