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

        return view('groups.listed_locations')
            ->with('group', $group)
            ->with('listed_locations', $group->getNamedLocations());
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
