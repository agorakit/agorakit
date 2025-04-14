<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Context;


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
            $groups = Context::getVisibleGroups();

            $actions = \App\Action::with('group')
                ->where('start', '>=', Carbon::now()->subDay())
                ->whereIn('group_id', $groups)
                ->orderBy('start');


            if (Auth::user()->getPreference('show', 'my') == 'all') {

                $actions->orWhere('visibility', 10);
            }

            $actions = $actions->paginate(20);

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
        $groups = Context::getVisibleGroups();

        // load of actions between start and stop provided by calendar js
        if ($request->has('start') && $request->has('end')) {
            $actions = \App\Action::with('group', 'attending')
                ->where('start', '>', Carbon::parse($request->get('start')))
                ->where('stop', '<', Carbon::parse($request->get('end')))
                ->whereIn('group_id', $groups)
                ->orderBy('start', 'asc');
        } else { // return current month
            $actions = \App\Action::with('group', 'attending')
                ->orderBy('start', 'asc')
                ->where('start', '>', Carbon::now()->subMonth())
                ->where('stop', '<', Carbon::now()->addMonth())
                ->whereIn('group_id', $groups);
        }

        if (Auth::check()) {
            if (Auth::user()->getPreference('show', 'my') == 'all') {
                $actions->orWhere('visibility', 10);
            }
        }

        $actions = $actions->get();

        $event = [];
        $events = [];

        foreach ($actions as $action) {
            $event['id'] = $action->id;
            $event['title'] = $action->name . ' (' . $action->group->name . ')';
            $event['description'] = strip_tags(summary($action->body)) . ' <br/> ' . $action->location_display();
            $event['body'] = strip_tags(summary($action->body));
            $event['summary'] = strip_tags(summary($action->body));

            $event['tooltip'] =  '<strong>' . strip_tags(summary($action->name)) . '</strong>';
            $event['tooltip'] .= '<div>' . strip_tags(summary($action->body)) . '</div>';

            if ($action->attending->count() > 0) {
                $event['tooltip'] .= '<strong class="mt-2">' . trans('messages.user_attending') . '</strong>';
                $event['tooltip'] .= '<div>' . implode(', ', $action->attending->pluck('username')->toArray()) . '</div>';
            }


            $event['location'] = $action->location_display();
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
