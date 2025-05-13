<?php

namespace App\Http\Controllers;

use Auth;
use App\Group;

use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class IcalController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Create new calendar
        $calendar = Calendar::create(setting('name'));

        // decide which groups to show
        if (Auth::check()) {
            $groups = Auth::user()->getVisibleGroups();
        } else {
            $groups = Group::public()->pluck('id');
        }

         // returns the 500 most recent events
        $events = \App\Event::with('group')
            ->whereIn('group_id', $groups)
            ->orderBy('start','desc')
            ->take(500)
            ->get();

        foreach ($events as $event) {
            // Create an event
            $event = Event::create()
                ->name($event->name)
                ->description(summary($event->body), 1000)
                ->uniqueIdentifier($event->group->name . '-' . $event->id)
                ->createdAt($event->created_at)
                ->startsAt($event->start)
                ->endsAt($event->stop)
                ->address($event->location_display());

            $calendar->event($event);
        }

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');

    }
}
