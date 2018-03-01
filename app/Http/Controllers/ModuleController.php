<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Group;


/**
* This controller taks care of enabling / disabling features (or modules) for each group
* A module is for example the discussion module
* In the future we might go the dreaded way of a plugin system
*/
class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('groupadmin');
    }


    public function edit(Request $request, Group $group)
    {
        return view('groups.modules')
        ->with('group', $group)
        ->with('tab', 'admin');
    }

    public function update(Request $request, Group $group)
    {
        if ($request->has('module_discussion'))
        {
            $group->setSetting('module_discussion', true);
        }
        else
        {
            $group->setSetting('module_discussion', false);
        }

        if ($request->has('module_action'))
        {
            $group->setSetting('module_action', true);
        }
        else
        {
            $group->setSetting('module_action', false);
        }

        if ($request->has('module_file'))
        {
            $group->setSetting('module_file', true);
        }
        else
        {
            $group->setSetting('module_file', false);
        }


        if ($request->has('module_member'))
        {
            $group->setSetting('module_member', true);
        }
        else
        {
            $group->setSetting('module_member', false);
        }

        if ($request->has('module_map'))
        {
            $group->setSetting('module_map', true);
        }
        else
        {
            $group->setSetting('module_map', false);
        }


        flash(trans('messages.ressource_updated_successfully'));

        return redirect()->route('groups.show', $group);
    }

}
