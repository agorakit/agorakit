<?php

namespace App\Http\Controllers;

use App\Group;
use Carbon\Carbon;
use Charts;

class GroupInsightsController extends Controller
{

    public function __construct()
    {
        $this->middleware('member');
    }


    public function index(Group $group)
    {
        // This is really a tribute to laravel efficiency and to the marvelous https://github.com/ConsoleTVs/Charts package

        $charts[] = Charts::create('bar', 'highcharts')
        ->title('General stats')
        ->labels(['Users', 'Discussions', 'Events', 'Files'])
        ->values([$group->users()->count(), $group->discussions()->count(), $group->actions()->count(), $group->files()->count()])
        ->dimensions(0, 400);

        $charts[] = Charts::database($group->actions()->get(), 'line', 'highcharts')
        ->title('Events per month')
        ->elementLabel('Events')
        ->dimensions(0, 400)
        ->dateColumn('start')
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1); // this finds the number of months since group creation. Clever isn't it ?

        $charts[] = Charts::database($group->discussions()->get(), 'line', 'highcharts')
        ->title('Discussions per month')
        ->elementLabel('Discussions')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);

        $charts[] = Charts::database($group->files()->get(), 'line', 'highcharts')
        ->title('Files per month')
        ->elementLabel('Files')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);

        $charts[] = Charts::database($group->memberships()->get(), 'line', 'highcharts')
        ->title('New memberships per month')
        ->elementLabel('Memberships')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);

        $charts[] = Charts::create('bar', 'highcharts')
        ->title('Storage use')
        ->labels(['Files'])
        ->values([$group->files()->sum('filesize')])
        ->dimensions(0, 400);


        return view('groups.insights')
        ->with('charts', $charts)
        ->with('group', $group);
    }

    
}
