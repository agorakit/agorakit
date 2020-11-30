<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;

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
        // 1. Create new calendar
        $vCalendar = new \Eluceo\iCal\Component\Calendar(config('app.url'));
        $vCalendar->setName(setting('name'));

        // decide which groups to show
        if (Auth::check()) {
            if (Auth::user()->getPreference('show') == 'all') {
                if (Auth::user()->isAdmin()) { // super admin sees everything
                    $groups = \App\Group::get()
                    ->pluck('id');
                } else {
                    $groups = \App\Group::public()
                    ->get()
                    ->pluck('id')
                    ->merge(Auth::user()->groups()->pluck('groups.id'));
                }
            } else {
                $groups = Auth::user()->groups()->pluck('groups.id');
            }
        } else {
            $groups = \App\Group::public()->get()->pluck('id');
        }

        // returns actions from the last 60 days
        $actions = \App\Action::with('group')
        ->whereIn('group_id', $groups)
        ->where('start', '>=', Carbon::now()->subDays(60))
        ->get();

        foreach ($actions as $action) {
            // 2. Create an event
            $vEvent = new \Eluceo\iCal\Component\Event();
            $vEvent->setDtStart($action->start);
            $vEvent->setDtEnd($action->stop);
            $vEvent->setSummary($action->name);
            $vEvent->setDescription(summary($action->body), 1000);
            $vEvent->setLocation($action->location);
            $vEvent->setUrl(route('groups.actions.show', [$action->group, $action]));
            $vEvent->setUseUtc(false); //TODO fixme

            $vCalendar->addComponent($vEvent);
        }

        return response($vCalendar->render())
        ->header('Content-Type', 'text/calendar; charset=utf-8')
        ->header('Content-Disposition', 'attachment; filename="cal.ics"');
    }
}
