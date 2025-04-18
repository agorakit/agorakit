<?php

namespace App\Http\Controllers;

use Auth;
use App\Group;
use Context;

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

        $groups = Context::getVisibleGroups();

         // returns the 500 most recent actions
        $actions = \App\Action::with('group')
            ->whereIn('group_id', $groups)
            ->orderBy('start','desc')
            ->take(500)
            ->get();

        foreach ($actions as $action) {
            // Create an event
            $event = Event::create()
                ->name($action->name)
                ->description(summary($action->body), 1000)
                ->uniqueIdentifier($action->group->name . '-' . $action->id)
                ->createdAt($action->created_at)
                ->startsAt($action->start)
                ->endsAt($action->stop)
                ->address($action->location_display());

            $calendar->event($event);
        }

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');

    }
}
