<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use Auth;
use Carbon\Carbon;

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

    if (Auth::check()) {

      if (Auth::user()->getPreference('show', 'my') == 'admin') {
        // build a list of groups the user has access to
        if (Auth::user()->isAdmin()) { // super admin sees everything
          $groups_id = Group::get()
            ->pluck('id');
        }
      }

      if (Auth::user()->getPreference('show', 'my') == 'all') {
        $groups_id = Group::public()
          ->get()
          ->pluck('id')
          ->merge(Auth::user()->groups()->pluck('groups.id'));
      }

      if (Auth::user()->getPreference('show', 'my') == 'my') {
        $groups_id = Auth::user()->groups()->pluck('groups.id');
      }
    } else {
      $groups_id = Group::public()
        ->get()
        ->pluck('id');
    }


    // Magic query to get all the users who have one of the groups defined above in their membership table
    $users = User::whereHas('groups', function ($q) use ($groups_id) {
      $q->whereIn('group_id', $groups_id);
    })
      ->where('verified', 1)
      ->where('latitude', '<>', 0)
      ->get();



    $actions = \App\Action::where('start', '>=', Carbon::now())
      ->where('latitude', '<>', 0)
      ->whereIn('group_id', $groups_id)
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

    foreach ($actions as $action) {
      $marker = [
        'type'       => 'Feature',
        'properties' => [
          'title'         => '<a href="' . route('groups.actions.show', [$action->group, $action]) . '">' . $action->name . '</a>',
          'description'   => summary($action->body),
          'type' => 'action',
        ],
        'geometry' => [
          'type'        => 'Point',
          'coordinates' => [
            $action->longitude,
            $action->latitude,

          ],
        ],
      ];
      array_push($geojson['features'], $marker);
    }

    $groups = Group::find($groups_id);

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
