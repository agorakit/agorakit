<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Group;

/**

 * Global listing of actions.
 */
class ActionController extends Controller
{
    public function __construct()
    {
        $this->middleware('preferences');
    }

    public function index(Request $request)
    {
        if (Auth::check()) {
            $view = Auth::user()->getPreference('calendar', 'grid');
        } else {
            $view = 'grid';
        }

        if ($view == 'list') {
            if (Auth::check()) {
                if (Auth::user()->getPreference('show', 'my') == 'admin') {
                    // build a list of groups the user has access to
                    if (Auth::user()->isAdmin()) { // super admin sees everything
                        $groups = Group::get()
                        ->pluck('id');
                    } 
                } 
    
                if (Auth::user()->getPreference('show', 'my') == 'all') {
                        $groups = Group::public()
                        ->get()
                        ->pluck('id')
                        ->merge(Auth::user()->groups()->pluck('groups.id'));
                } 
                
                if (Auth::user()->getPreference('show', 'my') == 'my') {
                    $groups = Auth::user()->groups()->pluck('groups.id');
                }
            } else {
                $groups = \App\Group::public()->get()->pluck('id');
            }

            $actions = \App\Action::with('group')
                ->where('start', '>=', Carbon::now())
                ->whereIn('group_id', $groups)
                ->orderBy('start')
                ->paginate(10);

            return view('dashboard.agenda-list')
                ->with('title', trans('messages.agenda'))
                ->with('tab', 'actions')
                ->with('actions', $actions);
        }

        return view('dashboard.agenda')
            ->with('title', trans('messages.agenda'))
            ->with('tab', 'actions');
    }

    public function indexJson(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->getPreference('show', 'my') == 'admin') {
                if (Auth::user()->isAdmin()) { // super admin sees everything
                    $groups = Group::get()
                    ->pluck('id');
                } 
            } 

            if (Auth::user()->getPreference('show', 'my') == 'all') {
                    $groups = Group::public()
                    ->get()
                    ->pluck('id')
                    ->merge(Auth::user()->groups()->pluck('groups.id'));
            } 
            
            if (Auth::user()->getPreference('show', 'my') == 'my') {
                $groups = Auth::user()->groups()->pluck('groups.id');
            }
        } else {
            $groups = \App\Group::public()->get()->pluck('id');
        }

        // load of actions between start and stop provided by calendar js
        if ($request->has('start') && $request->has('end')) {
            $actions = \App\Action::with('group')
                ->where('start', '>', Carbon::parse($request->get('start')))
                ->where('stop', '<', Carbon::parse($request->get('end')))
                ->whereIn('group_id', $groups)
                ->orderBy('start', 'asc')->get();
        } else {
            $actions = \App\Action::with('group')
                ->orderBy('start', 'asc')
                ->whereIn('group_id', $groups)
                ->get();
        }

        $event = [];
        $events = [];

        foreach ($actions as $action) {
            $event['id'] = $action->id;
            $event['title'] = $action->name;
            $event['description'] = $action->body . ' <br/> ' . $action->location;
            $event['body'] = filter($action->body);
            $event['summary'] = summary($action->body);
            $event['location'] = $action->location;
            $event['start'] = $action->start->toIso8601String();
            $event['end'] = $action->stop->toIso8601String();
            $event['url'] = route('groups.actions.show', [$action->group, $action]);
            $event['group_url'] = route('groups.actions.index', [$action->group]);
            $event['group_name'] = $action->group->name;
            $event['color'] = $action->group->color();

            $events[] = $event;
        }

        return $events;
    }
}
