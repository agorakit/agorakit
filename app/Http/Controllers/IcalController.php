<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IcalController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    // 1. Create new calendar
    $vCalendar = new \Eluceo\iCal\Component\Calendar(config('app.url'));

    // returns actions from the last 60 days
    $actions = \App\Action::with('group')->where('start', '>=', Carbon::now()->subDays(60))->get();


    foreach ($actions as $action)
    {
      // 2. Create an event
      $vEvent = new \Eluceo\iCal\Component\Event();
      $vEvent->setDtStart($action->start);
      $vEvent->setDtEnd($action->stop);
      $vEvent->setSummary($action->name);
      $vEvent->setDescription(summary($action->body), 1000);
      $vEvent->setLocation($action->location);
      $vEvent->setUrl(action('ActionController@show', [$action->group->id, $action->id]));
      $vEvent->setUseUtc(false); //TODO fixme

      $vCalendar->addComponent($vEvent);
    }

    return response($vCalendar->render())
    ->header('Content-Type', 'text/calendar; charset=utf-8')
    ->header('Content-Disposition', 'attachment; filename="cal.ics"');
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    //
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    //
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    //
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    //
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    //
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    //
  }
}
