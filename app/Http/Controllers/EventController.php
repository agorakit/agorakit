<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Context;


/**

 * Global listing of events.
 */
class EventController extends Controller
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

            $events = \App\Event::with('group')
                ->where('start', '>=', Carbon::now()->subDay())
                ->whereIn('group_id', $groups)
                ->orderBy('start');


            if (Auth::user()->getPreference('show', 'my') == 'all') {

                $events->orWhere('visibility', 10);
            }

            $events = $events->paginate(20);

            return view('dashboard.agenda-list')
                ->with('title', trans('messages.agenda'))
                ->with('tab', 'events')
                ->with('events', $events);
        }

        return view('dashboard.agenda')
            ->with('title', trans('messages.agenda'))
            ->with('tab', 'events');
    }

    public function indexJson(Request $request)
    {
        $groups = Context::getVisibleGroups();

        // load of events between start and stop provided by calendar js
        if ($request->has('start') && $request->has('end')) {
            $events = \App\Event::with('group', 'attending')
                ->where('start', '>', Carbon::parse($request->get('start')))
                ->where('stop', '<', Carbon::parse($request->get('end')))
                ->whereIn('group_id', $groups)
                ->orderBy('start', 'asc');
        } else { // return current month
            $events = \App\Event::with('group', 'attending')
                ->orderBy('start', 'asc')
                ->where('start', '>', Carbon::now()->subMonth())
                ->where('stop', '<', Carbon::now()->addMonth())
                ->whereIn('group_id', $groups);
        }

        if (Auth::check()) {
            if (Auth::user()->getPreference('show', 'my') == 'all') {
                $events->orWhere('visibility', 10);
            }
        }

        $events = $events->get();

        $event = [];
        $events = [];

        foreach ($events as $event) {
            $event['id'] = $event->id;
            $event['title'] = $event->name . ' (' . $event->group->name . ')';
            $event['description'] = strip_tags(summary($event->body)) . ' <br/> ' . $event->location_display();
            $event['body'] = strip_tags(summary($event->body));
            $event['summary'] = strip_tags(summary($event->body));

            $event['tooltip'] =  '<strong>' . strip_tags(summary($event->name)) . '</strong>';
            $event['tooltip'] .= '<div>' . strip_tags(summary($event->body)) . '</div>';

            if ($event->attending->count() > 0) {
                $event['tooltip'] .= '<strong class="mt-2">' . trans('messages.user_attending') . '</strong>';
                $event['tooltip'] .= '<div>' . implode(', ', $event->attending->pluck('username')->toArray()) . '</div>';
            }


            $event['location'] = $event->location_display();
            $event['start'] = $event->start->toIso8601String();
            $event['end'] = $event->stop->toIso8601String();
            $event['url'] = route('groups.events.show', [$event->group, $event]);
            $event['group_url'] = route('groups.events.index', [$event->group]);
            $event['group_name'] = $event->group->name;
            $event['color'] = $event->group->color();

            $events[] = $event;
        }

        return $events;
    }
}
