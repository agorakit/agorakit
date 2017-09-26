<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['users', 'files']]);
    }

    /**
     * Main HOMEPAGE.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $my_groups = Auth::user()->groups()->get();

            // other groups are public and not groups the user is member of
            $other_groups = \App\Group::publicgroups()->whereNotIn('id', $my_groups->pluck('id'))->get();

            // if user is not subscribed to any group, we redirect to group list homepage instead.
            if ($my_groups->count() == 0) {
                return redirect('groups');
            }

            $my_discussions = \App\Discussion::with('userReadDiscussion', 'user', 'group')
            ->has('user')
            ->whereIn('group_id', $my_groups->pluck('id'))
            ->orderBy('updated_at', 'desc')->paginate(10);

            $my_actions = \App\Action::with('user', 'group')
            ->whereIn('group_id', $my_groups->pluck('id'))
            ->where('start', '>=', Carbon::now())->orderBy('start', 'asc')->paginate(10);

            $other_discussions = \App\Discussion::with('userReadDiscussion', 'user', 'group')
            ->has('user')
            ->whereIn('group_id', $other_groups->pluck('id'))
            ->orderBy('updated_at', 'desc')->paginate(10);

            $other_actions = \App\Action::with('user', 'group')
            ->whereIn('group_id', $other_groups->pluck('id'))
            ->where('start', '>=', Carbon::now())->orderBy('start', 'asc')->paginate(10);

            $activities = \App\Activity::with('user', 'group', 'model')->orderBy('created_at', 'asc')->paginate(10);

            return view('dashboard.homepage')
            ->with('tab', 'homepage')
            ->with('my_groups', $my_groups)
            ->with('my_discussions', $my_discussions)
            ->with('my_actions', $my_actions)
            ->with('other_actions', $other_actions)
            ->with('other_discussions', $other_discussions)
            ->with('activities', $activities);

        } else {
            return view('dashboard.presentation')
            ->with('tab', 'homepage');
        }
    }

    public function activities()
    {
        $activities = \App\Activity::with('user', 'group', 'model')->orderBy('created_at', 'asc')->paginate(10);

        return view('dashboard.activities')
        ->with('tab', 'homepage')
        ->with('activities', $activities);
    }

    /**
     * Show all the files independant of groups.
     */
    public function files()
    {
        if (Auth::check()) {
            $groups = \App\Group::publicgroups()->get()->pluck('id')->merge(Auth::user()->groups()->pluck('groups.id'));

            $files = \App\File::with('group', 'user')
            ->where('item_type', '<>', \App\File::FOLDER)
            ->whereIn('group_id', $groups)
            ->orderBy('updated_at', 'desc')->paginate(25);
        }
        /*
        else
        {
            $files = \App\File::with('group', 'user')
            ->where('item_type', '<>', \App\File::FOLDER)
            ->whereIn('group_id', \App\Group::publicgroups()->get()->pluck('id'))
            ->orderBy('updated_at', 'desc')->paginate(25);
        }
        */

        return view('dashboard.files')
        ->with('tab', 'files')
        ->with('files', $files);
    }

    /**
     * Generates a list of unread discussions.
     */
    public function discussions()
    {
        if (Auth::check()) {
            // All the groups of a user : Auth::user()->groups()->pluck('groups.id')
            // All the public groups : Auth::user()->groups()->pluck('groups.id')

            // A merge of the two :

            $groups = \App\Group::publicgroups()->get()->pluck('id')->merge(Auth::user()->groups()->pluck('groups.id'));

            $discussions = \App\Discussion::with('userReadDiscussion', 'group', 'user')
            ->whereIn('group_id', $groups)
            ->orderBy('updated_at', 'desc')->paginate(25);
        } else {
            $discussions = \App\Discussion::with('group', 'user')
            ->whereIn('group_id', \App\Group::publicgroups()->get()->pluck('id'))
            ->orderBy('updated_at', 'desc')->paginate(25);
        }

        return view('dashboard.discussions')
        ->with('tab', 'discussions')
        ->with('discussions', $discussions);
    }

    public function agenda()
    {
        $actions = \App\Action::with('group')
        ->where('start', '>=', Carbon::now())
        ->get();

        return view('dashboard.agenda')
        ->with('tab', 'actions')
        ->with('actions', $actions);
    }

    public function agendaJson(Request $request)
    {
        // load of actions between start and stop provided by calendar js
        if ($request->has('start') && $request->has('end')) {
            $actions = \App\Action::with('group')
            ->where('start', '>', Carbon::parse($request->get('start')))
            ->where('stop', '<', Carbon::parse($request->get('end')))
            ->orderBy('start', 'asc')->get();
        } else {
            $actions = \App\Action::with('group')->orderBy('start', 'asc')->get();
        }

        $event = [];
        $events = [];

        foreach ($actions as $action) {
            $event['id'] = $action->id;
            $event['title'] = $action->name;
            $event['description'] = $action->body.' <br/> '.$action->location;
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

        return view('dashboard.users')
        ->with('tab', 'users')
        ->with('users', $users);
    }

    public function groups()
    {
        if (Auth::check()) {
            $groups = \App\Group::with('membership')->with('tags')->orderBy('updated_at', 'desc')->get();
        } else {
            $groups = \App\Group::with('tags')->orderBy('updated_at', 'desc')->get();
        }

        $tagService = app(\Cviebrock\EloquentTaggable\Services\TagService::class);

        return view('dashboard.groups')
        ->with('tab', 'groups')
        ->with('groups', $groups)
        ->with('all_tags', $tagService->getAllTags('App\Group'));
    }

    /**
     * Renders a map of all users (curently).
     */
    public function map()
    {
        $users = \App\User::where('latitude', '<>', 0)->get();
        $actions = \App\Action::where('start', '>=', Carbon::now())->where('latitude', '<>', 0)->get();
        $groups = \App\Group::where('latitude', '<>', 0)->get();

        // randomize users geolocation by a few meters
        foreach ($users as $user) {
            $user->latitude = $user->latitude + (mt_rand(0, 10) / 10000);
            $user->longitude = $user->longitude + (mt_rand(0, 10) / 10000);
        }

        return view('dashboard.map')
        ->with('users', $users)
        ->with('actions', $actions)
        ->with('groups', $groups)
        ->with('tab', 'map')
        ->with('latitude', 50.8503396) // TODO make configurable, curently it's Brussels
        ->with('longitude', 4.3517103);
    }
}
