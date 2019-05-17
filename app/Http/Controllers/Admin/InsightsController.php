<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Charts;

class InsightsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $group = \App\Group::orderBy('created_at', 'asc')->first();

        $charts[] = Charts::create('bar', 'highcharts')
        ->title('General stats')
        ->labels(['Groups', 'Users', 'Verified users', 'Discussions', 'Comments', 'Events', 'Files'])
        ->values([\App\Group::count(), \App\User::count(), \App\User::verified()->count(), \App\Discussion::count(), \App\Comment::count(), \App\Action::count(), \App\File::count()])
        ->dimensions(0, 400);

        // This is really a tribute to laravel efficiency and to the marvelous https://github.com/ConsoleTVs/Charts package
        $charts[] = Charts::database(\App\Action::all(), 'line', 'highcharts')
        ->title('Events per month')
        ->elementLabel('Events')
        ->dimensions(0, 400)
        ->dateColumn('start')
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1); // this finds the number of months since group creation. Clever isn't it ?

        $charts[] = Charts::database(\App\Discussion::get(), 'line', 'highcharts')
        ->title('Discussions per month')
        ->elementLabel('Discussions')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);

        $charts[] = Charts::database(\App\File::get(), 'line', 'highcharts')
        ->title('Files per month')
        ->elementLabel('Files')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);

        $charts[] = Charts::database(\App\Membership::get(), 'line', 'highcharts')
        ->title('New memberships per month')
        ->elementLabel('Memberships')
        ->dimensions(0, 400)
        ->lastByMonth($group->created_at->diffInMonths(Carbon::now()) + 1);

        $charts[] = Charts::database(\App\Membership::orderBy('notification_interval')->get(), 'bar', 'highcharts')
        ->title('How often members want to be notified ?')
        ->elementLabel('Members')
        ->dimensions(0, 400)
        ->groupBy('notification_interval', null, [60 => 'Every hour', 240 => 'Every 4 hours', 600 => 'Every 10 hours', 10080 => 'Every week', 1440 => 'Every day', 0 => 'No notifications', -1 => 'Never', 20160 => 'Every two weeks', 43200 => 'Every month']);

        $charts[] = Charts::create('bar', 'highcharts')
        ->title('Storage use')
        ->labels(['Files'])
        ->values([\App\File::all()->sum('filesize')])
        ->dimensions(0, 400);

        return view('admin.insights')
        ->with('charts', $charts)
        ->with('group', $group);
    }
}
