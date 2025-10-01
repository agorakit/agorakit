<?php

namespace App\Http\Controllers;

use App\Group;
use Carbon\Carbon;

/**
 * This controller is used to display various kind of maps (geo content).
 */
class GroupMapController extends Controller
{
  /**
   * Renders a map of all users (curently).
   */
    public function index(Group $group)
    {
        $this->authorize('view-members', $group);

        return view('groups.map')
        ->with('json', route('groups.map.geojson', $group))
        ->with('group', $group)
        ->with('tab', 'map');
    }

  /**
   * Renders a map of all users of a particular Group.
   */
    public function geoJson(Group $group)
    {
        $this->authorize('view-members', $group);
        $users = $group->users()->where('latitude', '<>', 0)->get();
        $events = $group->calendarevents()->where('stop', '>=', Carbon::now()->subDays(1))->where('latitude', '<>', 0)->get();

      // randomize users geolocation by a few meters
        foreach ($users as $user) {
            $user->latitude = $user->latitude + (mt_rand(0, 10) / 10000);
            $user->longitude = $user->longitude + (mt_rand(0, 10) / 10000);
        }

      // Generate GEOJSON
        $geojson = ['type' => 'FeatureCollection', 'features' => []];

        foreach ($users as $user) {
            $marker = [
            'type'       => 'Feature',
            'properties' => [
            'title'         => '<a href="' . route('users.show', $user) . '">' . $user->name . '</a>',
            'description'   => summary($user->body),
            'type' => 'user'
            ],
            'geometry' => [
            'type'        => 'Point',
            'coordinates' => [
            $user->longitude,
            $user->latitude,

            ],
            ],
            ];
            array_push($geojson['features'], $marker);
        }

        foreach ($events as $event) {
            $marker = [
            'type'       => 'Feature',
            'properties' => [
            'title'         => '<a href="' . route('groups.calendarevents.show', [$event->group, $event]) . '">' . $event->name . '</a>',
            'description'   => summary($event->body),
            'type' => 'calendarevent'
            ],
            'geometry' => [
            'type'        => 'Point',
            'coordinates' => [
            $event->longitude,
            $event->latitude,

            ],
            ],
            ];
            array_push($geojson['features'], $marker);
        }

      // add the current group on the map
        if ($group->latitude != 0) {
            $marker = [
            'type'       => 'Feature',
            'properties' => [
            'title'         => '<a href="' . route('groups.show', $group) . '">' . $group->name . '</a>',
            'description'   => summary($group->body),
            'type' => 'group'
            ],
            'geometry' => [
            'type'        => 'Point',
            'coordinates' => [
            $group->longitude,
            $group->latitude,

            ],
            ],
            ];
            array_push($geojson['features'], $marker);
        }

        return $geojson;
    }
}
