<?php

namespace App\Http\Controllers;

use App\Group;
use Carbon\Carbon;

class GroupIcalController extends Controller
{
    public function __construct()
    {
        $this->middleware('public');
    }

    /**
     * Renders an ical file for a specific group.
     */
    public function index(Group $group)
    {
        $this->authorize('view-actions', $group);
        // 1. Create new calendar
        $vCalendar = new \Eluceo\iCal\Component\Calendar(config('app.url'));
        $vCalendar->setName(config('agorakit.name').' : '.$group->name);
        $vCalendar->setDescription(summary($group->body, 500));

        // returns actions started the last 60 days
        $actions = $group->actions()->where('start', '>=', Carbon::now()->subDays(60))->get();

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
