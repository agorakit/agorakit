<?php

namespace App\Http\Controllers;

use App\Group;
use Carbon\Carbon;

/**
 * This controller is used to display various kind of maps (geo content).
 */
class MapController extends Controller
{
    /**
     * Renders a map of all users of a particular Group.
     */
    public function map(Group $group)
    {
        $users = $group->users()->where('latitude', '<>', 0)->get();
        $actions = $group->actions()->where('start', '>=', Carbon::now())->where('latitude', '<>', 0)->get();

        // randomize users geolocation by a few meters
        foreach ($users as $user) {
            $user->latitude = $user->latitude + (mt_rand(0, 10) / 10000);
            $user->longitude = $user->longitude + (mt_rand(0, 10) / 10000);
        }

        return view('groups.map')
        ->with('tab', 'map')
        ->with('group', $group)
        ->with('users', $users)
        ->with('actions', $actions)
        ->with('latitude', 50.8503396) // TODO make configurable, curently it's Brussels
        ->with('longitude', 4.3517103);
    }

    /**
     * Renders a embedable map.
     */
    public function embed(Group $group)
    {
        $users = $group->users()->where('latitude', '<>', 0)->get();
        $actions = $group->actions()->where('start', '>=', Carbon::now())->where('latitude', '<>', 0)->get();

        // randomize users geolocation by a few meters
        foreach ($users as $user) {
            $user->latitude = $user->latitude + (mt_rand(0, 10) / 10000);
            $user->longitude = $user->longitude + (mt_rand(0, 10) / 10000);
        }

        return view('groups.map_embed')
        ->with('tab', 'map')
        ->with('group', $group)
        ->with('users', $users)
        ->with('actions', $actions)
        ->with('latitude', 50.8503396) // TODO make configurable, curently it's Brussels
        ->with('longitude', 4.3517103);
    }
}
