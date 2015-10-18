<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;
use App\Group;

class ActionController extends Controller
{
    /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(REQUEST $request, $group_id)
  {
      $group = Group::findOrFail($group_id);
      $actions = $group->actions()->orderBy('start', 'asc')->get();

      $calendar = \Calendar::addEvents($actions);

      return view('actions.index')
      ->with('actions', $actions)
      ->with('calendar', $calendar)
      ->with('group', $group)
      ->with('tab', 'action');
  }

/**
 * Show the form for creating a new resource.
 *
 * @return Response
 */
 public function create(Request $request, $group_id)
 {
     $group = Group::findOrFail($group_id);

     return view('actions.create')
     ->with('group', $group)
     ->with('tab', 'action');
 }
/**
 * Store a newly created resource in storage.
 *
 * @return Response
 */
 public function store(Request $request, $group_id)
 {



     $action = new Action(Request::all());

     /*
     $action->name = $request->input('name');
     $action->body = $request->input('body');
     */

     $action->user()->associate(Auth::user());


     $group = Group::findOrFail($group_id);
     $group->actions()->save($action);

     return redirect()->action('ActionController@index', [$group->id]);
 }



/**
 * Display the specified resource.
 *
 * @param int $id
 *
 * @return Response
 */
 public function show($group_id, $action_id)
 {
     $action = action::findOrFail($action_id);
     $group = Group::findOrFail($group_id);



     //$comments = $action->comments;
     return view('actions.show')
     ->with('action', $action)
     ->with('group', $group)
     ->with('tab', 'action');
 }

 /**
 * Show the form for editing the specified resource.
 *
 * @param  int  $id
 *
 * @return Response
 */
 public function edit(Request $request, $group_id, $action_id)
 {
     $action = action::findOrFail($action_id);
     $group = $action->group;

     return view('actions.edit')
     ->with('action', $action)
     ->with('group', $group)
     ->with('tab', 'action');

 }

 /**
 * Update the specified resource in storage.
 *
 * @param  int  $id
 *
 * @return Response
 */
 public function update(Request $request, $group_id, $action_id)
 {
     $action = action::findOrFail($action_id);
     $action->name = $request->input('name');
     $action->body = $request->input('body');

     $action->user()->associate(Auth::user());

     $action->save();

     return redirect()->action('actionController@show', [$action->group->id]);
 }

/**
 * Remove the specified resource from storage.
 *
 * @param int $id
 *
 * @return Response
 */
public function destroy($id)
{
}
}
