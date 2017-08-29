<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Charts;
use \App\Group;

class InsightsController extends Controller
{

    public function index(Group $group)
    {

        // This is really a tribute to laravel efficiency and to the marvelous https://github.com/ConsoleTVs/Charts package
        $charts[] = Charts::database($group->actions()->get(), 'line', 'highcharts')
        ->title("Events per month")
        ->elementLabel('Events')
        ->dimensions(0, 400)
        ->dateColumn('start')
        ->groupByMonth();


        $charts[] = Charts::database($group->discussions()->get(), 'line', 'highcharts')
        ->title("Discussions per month")
        ->elementLabel('Discussions')
        ->dimensions(0, 400)
        ->groupByMonth();


        $charts[] = Charts::database($group->files()->get(), 'line', 'highcharts')
        ->title("Files per month")
        ->elementLabel('Files')
        ->dimensions(0, 400)
        ->groupByMonth();


        $charts[] = Charts::database($group->memberships()->get(), 'line', 'highcharts')
        ->title("Memberships per month")
        ->elementLabel('Memberships')
        ->dimensions(0, 400)
        ->groupByMonth();



        return view('groups.insights')
        ->with('charts', $charts)
        ->with('group', $group);

    }
}
