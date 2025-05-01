<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use Auth;
use Carbon\Carbon;
use Context;

class MapController extends Controller
{
  public function __construct()
  {
    $this->middleware('verified');
  }

  /**
   * Renders a map of all users (curently).
   */
  public function index()
  {
    $title = trans('messages.map');

    return view('dashboard.map')
      ->with('json', route('map.geojson'))
      ->with('tab', 'map')
      ->with('title', $title);
  }

  /**
   * Renders a map of all users (curently).
   */
  public function geoJson()
  {
    $groups = Context::getVisibleGroups();

    // Magic query to get all the users who have one of the groups defined above in their membership table
    $users = User::whereHas('groups', function ($q) use ($groups) {
      $q->whereIn('group_id', $groups);
    })
      ->where('verified', 1)
      ->where('latitude', '<>', 0)
      ->get();



    $events = \App\Event::where('stop', '>=', Carbon::now()->subDays(1))
      ->where('latitude', '<>', 0)
      ->whereIn('group_id', $groups)
      ->get();


    // randomize users geolocation by a few meters
    foreach ($users as $user) {
      $user->latitude = $user->latitude + (mt_rand(0, 10) / 10000);
      $user->longitude = $user->longitude + (mt_rand(0, 10) / 10000);
    }

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
          'title'         => '<a href="' . route('groups.events.show', [$event->group, $event]) . '">' . $event->name . '</a>',
          'description'   => summary($event->body),
          'type' => 'event',
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

    $groups = Group::find($groups);

    foreach ($groups as $group) {
      if ($group->latitude <> 0) {
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
    }

    return $geojson;
  }
}
