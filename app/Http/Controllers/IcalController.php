<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
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
            if (Auth::user()->getPreference('show') == 'all') {
                if (Auth::user()->isAdmin()) { // super admin sees everything
                    $groups = \App\Models\Group::get()
                        ->pluck('id');
                } else {
                    $groups = \App\Models\Group::public()
                        ->get()
                        ->pluck('id')
                        ->merge(Auth::user()->groups()->pluck('groups.id'));
                }
            } else {
                $groups = Auth::user()->groups()->pluck('groups.id');
            }
        } else {
            $groups = \App\Models\Group::public()->get()->pluck('id');
        }

        // returns actions from the last 60 days
        $actions = \App\Models\Action::with('group')
            ->whereIn('group_id', $groups)
            ->where('start', '>=', Carbon::now()->subDays(60))
            ->get();

        foreach ($actions as $action) {
            // Create an event
            $event = Event::create()
                ->name($action->name)
                ->description(summary($action->body), 1000)
                ->uniqueIdentifier($group->name.'-'.$action->id)
                ->createdAt($action->created_at)
                ->startsAt($action->start)
                ->endsAt($action->stop)
                ->address($action->location);

            $calendar->event($event);
        }

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');
    }
}
