<?php

namespace App\Http\Controllers;

use App\Group;
use Carbon\Carbon;

use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

class GroupIcalController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Renders an ical file for a specific group.
     */
    public function index(Group $group)
    {
        $this->authorize('view-events', $group);
        
        // Create new calendar
        $calendar = Calendar::create(setting('name') . ' : ' . $group->name);
        $calendar->description(summary($group->body, 500));



        // returns the 500 most recent events
        $events = $group->events()->orderBy('start', 'desc')->take(500)->get();

        foreach ($events as $event) {
            // Create an event
            $event = Event::create()
                ->name($event->name)
                ->description(summary($event->body), 1000)
                ->uniqueIdentifier($group->name . '-' . $event->id)
                ->createdAt($event->created_at)
                ->startsAt($event->start)
                ->endsAt($event->stop)
                ->address($event->location_display());

            $calendar->event($event);
        }

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');

        // or : 
        return response($calendar->get(), 200, [
            'Content-Type' => 'text/calendar',
            'Content-Disposition' => 'attachment; filename="my-awesome-calendar.ics"',
            'charset' => 'utf-8',
        ]);
    }
}
