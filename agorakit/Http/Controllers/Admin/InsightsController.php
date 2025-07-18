<?php

namespace Agorakit\Http\Controllers\Admin;

use Agorakit\Group;
use Agorakit\Http\Controllers\Controller;
use Carbon\Carbon;
use Agorakit\Charts\AgorakitChart;
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
        $chart->labels(['Groups', 'Active groups', 'Users', 'Verified users', 'Active users', 'Discussions', 'Comments', 'Events', 'Files']);
        $chart->dataset('Amount', 'bar', [
            \Agorakit\Group::count(),
            \Agorakit\Group::active()->count(),
            \Agorakit\User::count(),
            \Agorakit\User::verified()->count(),
            \Agorakit\User::active()->verified()->count(),
            \Agorakit\Discussion::count(),
            \Agorakit\Comment::count(),
            \Agorakit\Action::count(),
            \Agorakit\File::count()
        ]);
        $charts[] = $chart;




        // Discussions
        $results = \Agorakit\Discussion::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
            ->groupBy('yearmonth')
            ->orderBy('yearmonth', 'asc')
            ->get();

        $dataset = [];
        $labels = [];
        foreach ($results as $result) {
            $dataset[] = $result->data;
            $labels[] = $result->year . ' / ' .  $result->month;
        }

        $chart = new AgorakitChart;
        $chart->title('Discussions per month');
        $chart->labels($labels);
        $chart->dataset('Amount', 'line', $dataset);
        $charts[] = $chart;


        // Actions
        $results = \Agorakit\Action::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
            ->groupBy('yearmonth')
            ->orderBy('yearmonth', 'asc')
            ->get();

        $dataset = [];
        $labels = [];
        foreach ($results as $result) {
            $dataset[] = $result->data;
            $labels[] = $result->year . ' / ' .  $result->month;
        }

        $chart = new AgorakitChart;
        $chart->title('Actions per month');
        $chart->labels($labels);
        $chart->dataset('Amount', 'line', $dataset);
        $charts[] = $chart;


        // Members
        $results = \Agorakit\Membership::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
            ->groupBy('yearmonth')
            ->orderBy('yearmonth', 'asc')
            ->get();

        $dataset = [];
        $labels = [];
        foreach ($results as $result) {
            $dataset[] = $result->data;
            $labels[] = $result->year . ' / ' .  $result->month;
        }

        $chart = new AgorakitChart;
        $chart->title('Memberships per month');
        $chart->labels($labels);
        $chart->dataset('Amount', 'line', $dataset);
        $charts[] = $chart;



        // Files
        $results = \Agorakit\File::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
            ->groupBy('yearmonth')
            ->orderBy('yearmonth', 'asc')
            ->get();

        $dataset = [];
        $labels = [];
        foreach ($results as $result) {
            $dataset[] = $result->data;
            $labels[] = $result->year . ' / ' .  $result->month;
        }

        $chart = new AgorakitChart;
        $chart->title('Files per month');
        $chart->labels($labels);
        $chart->dataset('Amount', 'line', $dataset);
        $charts[] = $chart;



        // Evolution of storage use
        $results = \Agorakit\File::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, sum(filesize) as data')
            ->groupBy('yearmonth')
            ->orderBy('yearmonth', 'asc')
            ->get();

        $dataset = [];
        $labels = [];
        $total = 0;
        foreach ($results as $result) {
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
    }
}
