<?php

namespace App\Http\Controllers;

use App\User;
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
        return view('dashboard.map')
    ->with('tab', 'map');
    }

    /**
     * Renders a map of all users (curently).
     */
    public function geoJson()
    {
        // query all users from "my" group
        $groups = Auth::user()->groups()->pluck('groups.id');

        // Magic query to get all the users who have one of the groups defined above in their membership table
        $users = User::whereHas('groups', function ($q) use ($groups) {
            $q->whereIn('group_id', $groups);
        })
    ->where('verified', 1)
    ->where('latitude', '<>', 0)
    ->get();

        if (Auth::check()) {
            $allowed_groups = \App\Group::public()
      ->get()
      ->pluck('id')
      ->merge(Auth::user()->groups()->pluck('groups.id'));
        } else {
            $allowed_groups = \App\Group::public()->get()->pluck('id');
        }

        $actions = \App\Action::where('start', '>=', Carbon::now())
    ->where('latitude', '<>', 0)
    ->whereIn('group_id', $allowed_groups)
    ->get();

        $groups = \App\Group::where('latitude', '<>', 0)->get();

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
          'title'         => '<a href="'.route('users.show', $user).'">'.$user->name.'</a>',
          'description'   => summary($user->body),
          'marker-color'  => '#f95311',
          'marker-symbol' => 'pitch',
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
          'title'         => '<a href="'.route('groups.actions.show', [$action->group, $action]).'">'.$action->name.'</a>',
          'description'   => summary($action->body),
          'marker-color'  => '#4edd1f',
          'marker-symbol' => 'rocket',
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

        foreach ($groups as $group) {
            $marker = [
        'type'       => 'Feature',
        'properties' => [
          'title'         => '<a href="'.route('groups.show', $group).'">'.$group->name.'</a>',
          'description'   => summary($group->body),
          'marker-color'  => '#1f6edd',
          'marker-symbol' => 'warehouse',
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
