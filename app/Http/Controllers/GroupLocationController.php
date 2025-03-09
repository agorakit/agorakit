<?php

namespace App\Http\Controllers;

use App\Group;
use App\Location;
use Illuminate\Http\Request;

class GroupLocationController extends Controller
{
    public function __construct()
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, Group $group, Location $location)
    {
        $this->authorize('manage-locations', $group);

        // generate a complete list of locations, [with names], used in this group
        $locations = collect();

        $locations->push($group->location);

        $actions = $group->actions()
            ->with('location')
            ->get();

        foreach ($actions as $action) {
            $locations->push($location);
        }

        // FIXME unique sort by name
        //$locations = $locations->unique('normalized')->sortBy('normalized');


        return view('groups.allowed_locations')
            ->with('group', $group)
            ->with('locations', $locations)
            ->with('newLocationsAllowed', true)
            ->with('selectedLocations', $discussion->getAllowedLocations());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, Group $group)
    {
        $this->authorize('manage-locations', $group);
        $group->setSetting('listed_locations', $request->input('locations'));

        flash(trans('messages.ressource_updated_successfully'));
        return redirect()->route('groups.locations.edit', [$group]);
    }
}
