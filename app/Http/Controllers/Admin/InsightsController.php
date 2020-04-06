<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Charts\AgorakitChart;
use DB;

class InsightsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {

        // might be interesting to read this : https://www.plumislandmedia.net/mysql/sql-reporting-time-intervals/


        // Global stats :
        $chart = new AgorakitChart;
        $chart->title('General stats');
        $chart->labels(['Groups', 'Users', 'Verified users', 'Discussions', 'Comments', 'Events', 'Files']);
        $chart->dataset('Amount', 'bar', [\App\Group::count(), \App\User::count(), \App\User::verified()->count(), \App\Discussion::count(), \App\Comment::count(), \App\Action::count(), \App\File::count()]);
        $charts[] = $chart;




        // Discussions
        $results = \App\Discussion::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
        ->groupBy('yearmonth')
        ->orderBy('yearmonth', 'asc')
        ->get();

        $dataset = null;
        $labels = null;
        foreach ($results as $result)
        {
            $dataset[] = $result->data;
            $labels[] = $result->year . ' / ' .  $result->month;
        }

        $chart = new AgorakitChart;
        $chart->title('Discussions per month');
        $chart->labels($labels);
        $chart->dataset('Amount', 'line', $dataset);
        $charts[] = $chart;


        // Actions
        $results = \App\Action::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
        ->groupBy('yearmonth')
        ->orderBy('yearmonth', 'asc')
        ->get();

        $dataset = null;
        $labels = null;
        foreach ($results as $result)
        {
            $dataset[] = $result->data;
            $labels[] = $result->year . ' / ' .  $result->month;
        }

        $chart = new AgorakitChart;
        $chart->title('Actions per month');
        $chart->labels($labels);
        $chart->dataset('Amount', 'line', $dataset);
        $charts[] = $chart;


        // Actions
        $results = \App\Membership::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
        ->groupBy('yearmonth')
        ->orderBy('yearmonth', 'asc')
        ->get();

        $dataset = [];
        $labels = [];
        foreach ($results as $result)
        {
            $dataset[] = $result->data;
            $labels[] = $result->year . ' / ' .  $result->month;
        }

        $chart = new AgorakitChart;
        $chart->title('Memberships per month');
        $chart->labels($labels);
        $chart->dataset('Amount', 'line', $dataset);
        $charts[] = $chart;



        // Files
        $results = \App\File::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
        ->groupBy('yearmonth')
        ->orderBy('yearmonth', 'asc')
        ->get();

        $dataset = [];
        $labels = [];
        foreach ($results as $result)
        {
            $dataset[] = $result->data;
            $labels[] = $result->year . ' / ' .  $result->month;
        }

        $chart = new AgorakitChart;
        $chart->title('Files per month');
        $chart->labels($labels);
        $chart->dataset('Amount', 'line', $dataset);
        $charts[] = $chart;



        // Evolution of storage use
        $results = \App\File::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, sum(filesize) as data')
        ->groupBy('yearmonth')
        ->orderBy('yearmonth', 'asc')
        ->get();

        $dataset = [];
        $labels = [];
        $total = 0;
        foreach ($results as $result)
        {
            $total = $total + $result->data / 1000000;
            $dataset[] = round($total);
            $labels[] = $result->year . ' / ' .  $result->month;
        }

        $chart = new AgorakitChart;
        $chart->title('Evolution of storage use');
        $chart->labels($labels);
        $chart->dataset('Megabytes', 'line', $dataset);
        $charts[] = $chart;



        return view('admin.insights')
        ->with('charts', $charts);


        /*
        $Charts[] = Charts::create('bar', 'highCharts')
        ->title('General stats')
        ->labels(['Groups', 'Users', 'Verified users', 'Discussions', 'Comments', 'Events', 'Files'])
        ->values([\App\Group::count(), \App\User::count(), \App\User::verified()->count(), \App\Discussion::count(), \App\Comment::count(), \App\Action::count(), \App\File::count()])
        ->dimensions(0, 400);

        // This is really a tribute to laravel efficiency and to the marvelous https://github.com/ConsoleTVs/Charts package
        $Charts[] = Charts::database(\App\Action::all(), 'line', 'highCharts')
        ->title('Events per month')
        ->elementLabel('Events')
        ->dimensions(0, 400)
        ->dateColumn('start')
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1); // this finds the number of months since group creation. Clever isn't it ?

        $Charts[] = Charts::database(\App\Discussion::get(), 'line', 'highCharts')
        ->title('Discussions per month')
        ->elementLabel('Discussions')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);

        $Charts[] = Charts::database(\App\File::get(), 'line', 'highCharts')
        ->title('Files per month')
        ->elementLabel('Files')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);

        $Charts[] = Charts::database(\App\Membership::get(), 'line', 'highCharts')
        ->title('New memberships per month')
        ->elementLabel('Memberships')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);

        $Charts[] = Charts::database(\App\Membership::orderBy('notification_interval')->get(), 'bar', 'highCharts')
        ->title('How often members want to be notified ?')
        ->elementLabel('Members')
        ->dimensions(0, 400)
        ->groupBy('notification_interval', null, [60 => 'Every hour', 240 => 'Every 4 hours', 600 => 'Every 10 hours', 10080 => 'Every week', 1440 => 'Every day', 0 => 'No notifications', -1 => 'Never', 20160 => 'Every two weeks', 43200 => 'Every month']);

        $Charts[] = Charts::create('bar', 'highCharts')
        ->title('Storage use')
        ->labels(['Files'])
        ->values([\App\File::all()->sum('filesize')])
        ->dimensions(0, 400);
        */
        return view('admin.insights')
        ->with('Charts', $Charts)
        ->with('group', $group);
    }
}
