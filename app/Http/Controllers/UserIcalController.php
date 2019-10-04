<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        // 1. Create new calendar
        $vCalendar = new \Eluceo\iCal\Component\Calendar(config('app.url'));
        $vCalendar->setName(config('agorakit.name'));

        // groups are all groups from the current user
        $groups = $user->groups()->pluck('groups.id');

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
