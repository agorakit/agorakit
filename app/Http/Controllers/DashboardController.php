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

    public function __construct()
    {
        $this->middleware('auth', ['only' => ['discussions', 'users']]);
    }

    /**
    * Main HOMEPAGE
    *
    * @return Response
    */
    public function index(Request $request)
    {

        if (Auth::check())
        {

            // handle show all stuff or only from "my group"
            if ($request->input('show') == 'all')
            {
                Auth::user()->setPreference('show', 'all');
            }
            if ($request->input('show') == 'my')
            {
                Auth::user()->setPreference('show', 'my');
            }

            if (Auth::user()->getPreference('show', 'all') == 'all')
            { // we show everything
                $my_groups = Auth::user()->groups()->orderBy('name')->paginate(50);
                $groups = \App\Group::with('membership')->orderBy('name')->paginate(50);
                $all_discussions = \App\Discussion::with('userReadDiscussion', 'user', 'group')->orderBy('updated_at', 'desc')->paginate(25);
                $all_actions = \App\Action::with('user', 'group')->where('start', '>=', Carbon::now())->orderBy('start', 'asc')->paginate(25);
            }
            else // we show only content from the user's groups
            {
                $my_groups = Auth::user()->groups()->orderBy('name')->get();

                $my_groups_id = false;
                // using this array we can adjust the queries after to only include stuff the user has
                // might be a good idea to find a clever way to build this array of groups id :
                foreach ($my_groups as $the_group)
                {
                    $my_groups_id[] = $the_group->id;
                }

                $groups = \App\Group::with('membership')->orderBy('name')->paginate(50);

                $all_discussions = \App\Discussion::with('userReadDiscussion', 'user', 'group')
                ->whereIn('group_id', $my_groups_id)
                ->orderBy('updated_at', 'desc')->paginate(25);

                $all_actions = \App\Action::with('user', 'group')
                ->whereIn('group_id', $my_groups_id)
                ->where('start', '>=', Carbon::now())->orderBy('start', 'asc')->paginate(25);
            }
        }
        else
        {
            $groups = \App\Group::orderBy('name')->paginate(50);
            $all_discussions = \App\Discussion::with('user', 'group')->orderBy('updated_at', 'desc')->paginate(10);
            $all_actions = \App\Action::with('user', 'group')->where('start', '>=', Carbon::now())->orderBy('start', 'asc')->paginate(10);
            $my_groups = false;
        }



        return view('dashboard.homepage')
        ->with('groups', $groups)
        ->with('my_groups', $my_groups)
        ->with('all_discussions', $all_discussions)
        ->with('all_actions', $all_actions);
    }

    /**
    * Generates a list of unread discussions.
    */
    public function discussions()
    {

        $my_groups = Auth::user()->groups()->orderBy('name')->get();
        $my_groups_id = false;
        // using this array we can adjust the queries after to only include stuff the user has
        // might be a good idea to find a clever way to build this array of groups id :
        foreach ($my_groups as $the_group)
        {
            $my_groups_id[] = $the_group->id;
        }

        $discussions = \App\Discussion::with('userReadDiscussion', 'group')
        ->whereIn('group_id', $my_groups_id)
        ->orderBy('updated_at', 'desc')->paginate(25);


        return view('dashboard.discussions')
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


    public function users()
    {
        $users = \App\User::with('groups')->orderBy('name')->paginate(30);
        return view('dashboard.users')->with('users', $users);
    }


    /**
     * Renders a map of all users (curently)
     */
    public function map()
    {
        $users = \App\User::where('latitude', '<>', 0)->get();
        $actions = \App\Action::where('start', '>=', Carbon::now())->where('latitude', '<>', 0)->get();
        $groups = \App\Group::where('latitude', '<>', 0)->get();


        return view('dashboard.map')
        ->with('users', $users)
        ->with('actions', $actions)
        ->with('groups', $groups)
        ->with('latitude', 50.8503396) // TODO make configurable, curently it's Brussels
        ->with('longitude', 4.3517103);
    }


}
