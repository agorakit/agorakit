<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Context;
use App\Services\CalendarEventService;

/**

 * Global listing of events.
 */
class CalendarEventController extends Controller
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

            $events = \App\CalendarEvent::with('group')
                ->where('start', '>=', Carbon::now()->subDay())
                ->whereIn('group_id', $groups)
                ->orderBy('start');


            if (Auth::user()->getPreference('show', 'joined') == 'all') {
                $events->orWhere('visibility', 10);
            }

            $events = $events->paginate(20);

            return view('dashboard.calendar-list')
                ->with('title', trans('messages.calendar'))
                ->with('tab', 'calendarevents')
                ->with('events', $events);
        }

        return view('dashboard.calendar')
            ->with('title', trans('messages.calendar'))
            ->with('tab', 'calendarevents');
    }

    public function indexJson(Request $request)
    {
        $groups = Context::getVisibleGroups();

        // load of events between start and stop provided by calendar js
        if ($request->has('start') && $request->has('end')) {
            $events = \App\CalendarEvent::with('group', 'attending')
                ->where('start', '>', Carbon::parse($request->get('start')))
                ->where('stop', '<', Carbon::parse($request->get('end')))
                ->whereIn('group_id', $groups)
                ->orderBy('start', 'asc');
        } else { // return current month
            $events = \App\CalendarEvent::with('group', 'attending')
                ->orderBy('start', 'asc')
                ->where('start', '>', Carbon::now()->subMonth())
                ->where('stop', '<', Carbon::now()->addMonth())
                ->whereIn('group_id', $groups);
        }

        if (Auth::check()) {
            if (Auth::user()->getPreference('show', 'joined') == 'all') {
                $events->orWhere('visibility', 10);
            }
        }

        $events = $events->get();

        return CalendarEventService::calendarEventsToFullCallendarJson($events);
    }
}
