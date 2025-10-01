<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

/**
 * This controller provides a specific Ical url for each user.
 * The feed is signed so is kept private (as long as the url is kept private as well).
 */
class UserIcalController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        if (!$request->hasValidSignature()) {
            abort(404, 'Invalid signature');
        }


        // Create new calendar
        $calendar = Calendar::create(setting('name'));

        // groups are all groups from the current user
        $groups = $user->groups()->pluck('groups.id');

        //  // returns the 500 most recent Agorakit events
        $events = \App\CalendarEvent::with('group')
            ->whereIn('group_id', $groups)
            ->orderBy('start', 'desc')
            ->take(500)
            ->get();

        foreach ($events as $event) {
            // Create an Icalendar event
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
