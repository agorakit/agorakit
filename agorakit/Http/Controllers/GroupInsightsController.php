<?php

namespace App\Http\Controllers;

use App\Group;
use Carbon\Carbon;
use App\Charts\AgorakitChart;
use DB;

class GroupInsightsController extends Controller
{
    public function __construct()
    {
    }

    public function index(Group $group)
    {
        $this->authorize('administer', $group);

        // Global stats :
        $chart = new AgorakitChart;
        $chart->title('General stats');
        $chart->labels(['Users', 'Active users', 'Discussions', 'Events', 'Files']);
        $chart->dataset('Amount', 'bar', [
            $group->users()->count(),
            $group->users()->active()->count(),
            $group->discussions()->count(),
            $group->actions()->count(),
            $group->files()->count()
        ]);
        $charts[] = $chart;


        // Discussions
        $results = \App\Discussion::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
            ->groupBy('yearmonth')
            ->orderBy('yearmonth', 'asc')
            ->where('group_id', $group->id)
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
        $results = \App\Action::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
            ->groupBy('yearmonth')
            ->orderBy('yearmonth', 'asc')
            ->where('group_id', $group->id)
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
        $results = \App\Membership::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
            ->groupBy('yearmonth')
            ->orderBy('yearmonth', 'asc')
            ->where('group_id', $group->id)
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
        $results = \App\File::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, count(*) data')
            ->groupBy('yearmonth')
            ->orderBy('yearmonth', 'asc')
            ->where('group_id', $group->id)
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
        $results = \App\File::selectRaw('year(created_at) year, extract(YEAR_MONTH FROM created_at) AS yearmonth, monthname(created_at) month, sum(filesize) as data')
            ->groupBy('yearmonth')
            ->orderBy('yearmonth', 'asc')
            ->where('group_id', $group->id)
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



        return view('groups.insights')
            ->with('charts', $charts)
            ->with('group', $group);
    }
}
