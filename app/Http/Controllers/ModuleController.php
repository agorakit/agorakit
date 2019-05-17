<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

/**
 * This controller taks care of enabling / disabling features (or modules) for each group
 * A module is for example the discussion module
 * In the future we might go the dreaded way of a plugin system.
 */
class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('groupadmin', ['except' => ['show']]);
        $this->middleware('member');
    }

    public function show(Request $request, Group $group)
    {
        $this->authorize('administer', $group);
        if ($group->getSetting('module_custom_name')) {
            return view('groups.custom')
            ->with('group', $group)
            ->with('tab', 'custom');
        } else {
            abort(404, 'No custom module for this group');
        }
    }

    public function edit(Request $request, Group $group)
    {
        $this->authorize('administer', $group);

        return view('groups.modules')
        ->with('group', $group)
        ->with('tab', 'admin');
    }

    public function update(Request $request, Group $group)
    {
        $this->authorize('administer', $group);
        
        if ($request->has('module_discussion')) {
            $group->setSetting('module_discussion', true);
        } else {
            $group->setSetting('module_discussion', false);
        }

        if ($request->has('module_action')) {
            $group->setSetting('module_action', true);
        } else {
            $group->setSetting('module_action', false);
        }

        if ($request->has('module_file')) {
            $group->setSetting('module_file', true);
        } else {
            $group->setSetting('module_file', false);
        }

        if ($request->has('module_member')) {
            $group->setSetting('module_member', true);
        } else {
            $group->setSetting('module_member', false);
        }

        if ($request->has('module_map')) {
            $group->setSetting('module_map', true);
        } else {
            $group->setSetting('module_map', false);
        }

        // handle custom module (iframe or similar system)
        $group->setSetting('module_custom_icon', $request->get('module_custom_icon'));
        $group->setSetting('module_custom_name', $request->get('module_custom_name'));
        $group->setSetting('module_custom_html', $request->get('module_custom_html'));

        flash(trans('messages.ressource_updated_successfully'));

        return redirect()->route('groups.show', $group);
    }
}
