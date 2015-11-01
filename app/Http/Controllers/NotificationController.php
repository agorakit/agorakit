<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\QueryHelper;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class NotificationController extends Controller
{
  /**
   * Testing how a notification email would work and preview
   *
   * @return \Illuminate\Http\Response
   */
  public function notify($group_id)
  {
      // TODO remove this
      $group = \App\Group::findOrFail($group_id);

      // Establish timestamp for notifications from membership data (when was an email sent for the last time?)

      $membership = \App\Membership::where('user_id', '=', Auth::user()->id)
      ->where('group_id', "=", $group->id)->firstOrFail();

      // find unread discussions since timestamp
      $discussions = QueryHelper::getUnreadDiscussionsSince(Auth::user()->id, $group->id, $membership->notified_at);


      // find new files since timestamp
      $files = \App\File::where('updated_at', '>', $membership->notified_at)
      ->where('group_id', "=", $group->id)->get();


      // find new members since timestamp
      $users = QueryHelper::getNewMembersSince(Auth::user()->id, $group->id, $membership->notified_at);

      // find future actions until next mail timestamp
      // TODO must be done on the start and stop date
      $actions = \App\Action::where('start', '>', Carbon::now())
      ->where('group_id', "=", $group->id)->get();



      return view('emails.notification')
      ->with('user', Auth::user())
      ->with('group', $group)
      ->with('membership', $membership)
      ->with('discussions', $discussions)
      ->with('files', $files)
      ->with('users', $users)
      ->with('actions', $actions);


      // if we have anything, build the message and send

      // in all cases update timestamp

  }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
