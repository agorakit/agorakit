<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;
use App\Group;
use Carbon\Carbon;
use Auth;

class ActionController extends Controller
{

  public function __construct()
  {
    $this->middleware('member', ['only' => ['post', 'create', 'store', 'edit', 'update', 'destroy']]);
    $this->middleware('cache', ['only' => ['index', 'show']]);
  }

  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index(REQUEST $request, $group_id)
  {
    $group = Group::findOrFail($group_id);
    $actions = $group->actions()->orderBy('start', 'asc')->get();

    return view('actions.index')
    ->with('actions', $actions)
    ->with('group', $group)
    ->with('tab', 'action');
  }



  public function indexJson(REQUEST $request, $group_id)
  {
    // TODO ajax load of actions or similar trick, else the json will become larger and larger
    $group = Group::findOrFail($group_id);
    $actions = $group->actions()->orderBy('start', 'asc')->get();

    $event = '';
    $events = '';

    foreach ($actions as $action)
    {
      $event['id'] = $action->id;
      $event['title'] = $action->name . ' (' . $group->name . ')';
      $event['description'] = $action->body . ' <br/> ' . $action->location;
      $event['body'] = $action->body;
      $event['location'] = $action->location;
      $event['start'] = $action->start->toIso8601String();
      $event['end'] = $action->stop->toIso8601String();
      $event['url'] = action('ActionController@show', [$group->id, $action->id]);

      $events[] = $event;
    }
    return $events;
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

    $action = new Action();


    $action->name = $request->input('name');
    $action->body = clean($request->input('body'));
    $action->location = $request->input('location');
    $action->start = Carbon::createFromFormat('Y-m-d H:i', $request->input('start'));
    $action->stop = Carbon::createFromFormat('Y-m-d H:i', $request->input('stop'));


    $action->user()->associate($request->user());

    if ( $action->isInvalid())
    {
      // Oops.
      return redirect()->action('ActionController@create', $group_id)
      ->withErrors($action->getErrors())
      ->withInput();
    }

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
    $action->body = clean($request->input('body'));
    $action->location = $request->input('location');
    $action->start = Carbon::createFromFormat('Y-m-d H:i', $request->input('start'));
    $action->stop = Carbon::createFromFormat('Y-m-d H:i', $request->input('stop'));


    $action->user()->associate($request->user());

    if ( $action->isInvalid())
    {
      // Oops.
      return redirect()->action('ActionController@create', $group_id)
      ->withErrors($action->getErrors())
      ->withInput();
    }

    $action->save();


    return redirect()->action('ActionController@show', [$action->group->id, $action->id]);
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
