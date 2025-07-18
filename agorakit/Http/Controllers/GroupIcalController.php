<?php

namespace Agorakit\Http\Controllers;

use Agorakit\Group;
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
        $this->authorize('view-actions', $group);

        // Create new calendar
        $calendar = Calendar::create(setting('name') . ' : ' . $group->name);
        $calendar->description(summary($group->body, 500));



        // returns the 500 most recent actions
        $actions = $group->actions()->orderBy('start', 'desc')->take(500)->get();

        foreach ($actions as $action) {
            // Create an event
            $event = Event::create()
                ->name($action->name)
                ->description(summary($action->body), 1000)
                ->uniqueIdentifier($group->name . '-' . $action->id)
                ->createdAt($action->created_at)
                ->startsAt($action->start)
                ->endsAt($action->stop)
                ->address($action->location_display());

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
