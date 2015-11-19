<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\QueryHelper;
use Carbon\Carbon;

class DashboardController extends Controller
{
  /**
  * Generates a list of unread discussions.
  */
  public function unreadDiscussions()
  {

    $discussions = QueryHelper::getUnreadDiscussions();

    foreach ($discussions as $discussion)
    {
      $discussion->updated_at_human = Carbon::parse($discussion->updated_at)->diffForHumans();
    }

    return view('dashboard.unread')
    ->with('discussions', $discussions);
  }

  public function agenda()
  {
    //$actions = \App\Action::with('group')->where('start', '>=', Carbon::now())->get();
    $actions = \App\Action::with('group')->where('start', '>=', Carbon::now())->get();

    return view('dashboard.agenda')->with('actions', $actions);
  }

  public function agendaJson()
  {
    // TODO ajax load of actions or similar trick, else the json will become larger and larger
    $actions = \App\Action::with('group')->get();

    $event = '';
    $events = '';

    foreach ($actions as $action)
    {
      $event['id'] = $action->id;
      $event['title'] = $action->name;
      $event['description'] = $action->body . ' <br/> ' . $action->location;
      $event['body'] = $action->body;
      $event['summary'] = $action->summary();
      $event['location'] = $action->location;
      $event['start'] = $action->start->toIso8601String();
      $event['end'] = $action->stop->toIso8601String();
      $event['url'] = action('ActionController@show', [$action->group->id, $action->id]);
      $event['group_url'] = action('ActionController@index', [$action->group->id]);
      $event['group_name'] = $action->group->name;
      $event['color'] = $action->group->color();

      $events[] = $event;
    }
    return $events;


  }



}
