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
    return view('groups.map')
    ->with('group', $group)
    ->with('tab', 'map');
  }

  /**
  * Renders a map of all users of a particular Group.
  */
  public function geoJson(Group $group)
  {
    $users = $group->users()->where('latitude', '<>', 0)->get();
    $actions = $group->actions()->where('start', '>=', Carbon::now())->where('latitude', '<>', 0)->get();

    // randomize users geolocation by a few meters
    foreach ($users as $user) {
      $user->latitude = $user->latitude + (mt_rand(0, 10) / 10000);
      $user->longitude = $user->longitude + (mt_rand(0, 10) / 10000);
    }


    // Generate GEOJSON
    $geojson = array( 'type' => 'FeatureCollection', 'features' => array());

    foreach ($users as $user) {
      $marker = array(
        'type' => 'Feature',
        'properties' => array(
          'title' => '<a href="' . route('users.show', $user) . '">' . $user->name . '</a>',
          'description' => summary($user->body) ,
          'marker-color' => '#f95311',
          'marker-symbol' => 'pitch',
        ),
        'geometry' => array(
          'type' => 'Point',
          'coordinates' => array(
            $user->longitude,
            $user->latitude

          )
        )
      );
      array_push($geojson['features'], $marker);
    }

    foreach ($actions as $action) {
      $marker = array(
        'type' => 'Feature',
        'properties' => array(
          'title' => '<a href="' . route('groups.actions.show', [$action->group, $action]) . '">' . $action->name . '</a>',
          'description' => summary($action->body) ,
          'marker-color' => '#4edd1f',
          'marker-symbol' => 'rocket',
        ),
        'geometry' => array(
          'type' => 'Point',
          'coordinates' => array(
            $action->longitude,
            $action->latitude

          )
        )
      );
      array_push($geojson['features'], $marker);
    }



    // add the current group on the map
    if ($group->latitude <> 0)
    {
      $marker = array(
        'type' => 'Feature',
        'properties' => array(
          'title' => '<a href="' . route('groups.show', $group) . '">' . $group->name . '</a>',
          'description' => summary($group->body) ,
          'marker-color' => '#1f6edd',
          'marker-symbol' => 'warehouse',
        ),
        'geometry' => array(
          'type' => 'Point',
          'coordinates' => array(
            $group->longitude,
            $group->latitude

          )
        )
      );
      array_push($geojson['features'], $marker);
    }

    return $geojson;
  }



}
