<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\QueryHelper;
use Carbon\Carbon;
use Auth;

class DashboardController extends Controller
{


  /**
  * Main HOMEPAGE
  *
  * @return Response
  */
  public function index(Request $request)
  {
    if (Auth::check())
    {
      $groups = \App\Group::with('membership')->orderBy('name')->paginate(50);
      $my_groups = Auth::user()->groups()->orderBy('name')->paginate(50);



      $all_discussions = \App\Discussion::with('userReadDiscussion', 'user', 'group')->orderBy('updated_at', 'desc')->paginate(10);

      //dd($all_discussions);

      return view('dashboard.homepage')
      ->with('groups', $groups)
      ->with('my_groups', $my_groups)
      ->with('all_discussions', $all_discussions);
    }
    else
    {
      $groups = \App\Group::orderBy('name')->paginate(50);
      return view('dashboard.homepage')
      ->with('groups', $groups);
    }


  }



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
      $event['body'] = filter($action->body);
      $event['summary'] = summary($action->body);
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
