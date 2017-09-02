<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Charts;
use Carbon\Carbon;
use \App\Group;

class InsightsController extends Controller
{



    public function forGroup(Group $group)
    {

        // This is really a tribute to laravel efficiency and to the marvelous https://github.com/ConsoleTVs/Charts package
        $charts[] = Charts::database($group->actions()->get(), 'line', 'highcharts')
        ->title("Events per month")
        ->elementLabel('Events')
        ->dimensions(0, 400)
        ->dateColumn('start')
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1); // this finds the number of months since group creation. Clever isn't it ?


        $charts[] = Charts::database($group->discussions()->get(), 'line', 'highcharts')
        ->title("Discussions per month")
        ->elementLabel('Discussions')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);


        $charts[] = Charts::database($group->files()->get(), 'line', 'highcharts')
        ->title("Files per month")
        ->elementLabel('Files')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);


        $charts[] = Charts::database($group->memberships()->get(), 'line', 'highcharts')
        ->title("New memberships per month")
        ->elementLabel('Memberships')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);



        return view('groups.insights')
        ->with('charts', $charts)
        ->with('group', $group);
    }


    public function forAllGroups()
    {
        $group = \App\Group::orderBy('created_at', 'asc')->first();

        // This is really a tribute to laravel efficiency and to the marvelous https://github.com/ConsoleTVs/Charts package
        $charts[] = Charts::database(\App\Action::all(), 'line', 'highcharts')
        ->title("Events per month")
        ->elementLabel('Events')
        ->dimensions(0, 400)
        ->dateColumn('start')
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1); // this finds the number of months since group creation. Clever isn't it ?


        $charts[] = Charts::database(\App\Discussion::get(), 'line', 'highcharts')
        ->title("Discussions per month")
        ->elementLabel('Discussions')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now())  + 1);


        $charts[] = Charts::database(\App\File::get(), 'line', 'highcharts')
        ->title("Files per month")
        ->elementLabel('Files')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);


        $charts[] = Charts::database(\App\Membership::get(), 'line', 'highcharts')
        ->title("New memberships per month")
        ->elementLabel('Memberships')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);


        $charts[] = Charts::database(\App\Membership::get(), 'bar', 'highcharts')
        ->title("Memberships notifications")
        ->elementLabel('Members')
        ->dimensions(0, 400)
        ->groupBy('notification_interval');



        return view('admin.insights')
        ->with('charts', $charts)
        ->with('group', $group);
    }




}
