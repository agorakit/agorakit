<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;
use App\Group;
use Carbon\Carbon;
use Auth;
use Gate;

class ActionController extends Controller
{

    public function __construct()
    {
        $this->middleware('member', ['only' => ['post', 'create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('cache', ['only' => ['index', 'show']]);
        $this->middleware('verified', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('public', ['only' => ['index', 'indexJson', 'show']]);
    }

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index(Request $request, Group $group)
    {
        $actions = $group->actions()->orderBy('start', 'asc')->get();
        return view('actions.index')
        ->with('actions', $actions)
        ->with('group', $group)
        ->with('tab', 'action');
    }



    public function indexJson(Request $request, Group $group)
    {

        //dd($request);

        // load of actions between start and stop provided by calendar js
        if ($request->has('start') && $request->has('end'))
        {
            $actions = $group->actions()
            ->where('start', '>', Carbon::parse($request->get('start')))
            ->where('stop', '<', Carbon::parse($request->get('end')))
            ->orderBy('start', 'asc')->get();
        }
        else
        {

            $actions = $group->actions()->orderBy('start', 'asc')->get();
        }


        $event = '';
        $events = '';

        foreach ($actions as $action)
        {
            $event['id'] = $action->id;
            $event['title'] = $action->name . ' (' . $group->name . ')';
            $event['description'] = $action->body . ' <br/> ' . $action->location;
            $event['body'] = $action->body;
            $event['summary'] = summary($action->body);
            $event['location'] = $action->location;
            $event['start'] = $action->start->toIso8601String();
            $event['end'] = $action->stop->toIso8601String();
            $event['url'] = action('ActionController@show', [$group->id, $action->id]);
            $event['color'] = $group->color();

            $events[] = $event;
        }
        return $events;
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function create(Request $request, Group $group)
    {
        $action = new Action;

        if ($request->get('start'))
        {
            $action->start = Carbon::createFromFormat('Y-m-d H:i', $request->get('start'));
        }

        if ($request->get('stop'))
        {
            $action->stop = Carbon::createFromFormat('Y-m-d H:i', $request->get('stop'));
        }

        if ($request->get('title'))
        {
            $action->name = $request->get('title');
        }

        return view('actions.create')
        ->with('action', $action)
        ->with('group', $group)
        ->with('tab', 'action');
    }
    /**
    * Store a newly created resource in storage.
    *
    * @return Response
    */
    public function store(Request $request, Group $group)
    {
        $action = new Action();

        $action->name = $request->input('name');
        $action->body = $request->input('body');

        $action->start = Carbon::createFromFormat('Y-m-d H:i', $request->input('start'));
        $action->stop = Carbon::createFromFormat('Y-m-d H:i', $request->input('stop'));

        if ($request->get('location'))
        {
            $action->location = $request->input('location');
            if (!$action->geocode())
            {
                flash()->error(trans('messages.address_cannot_be_geocoded'));
            }
            else
            {
                flash()->info( trans('messages.ressource_geocoded_successfully'));
            }
        }


        $action->user()->associate($request->user());

        $action->group()->associate($group);

        if ( $action->isInvalid())
        {
            // Oops.
            return redirect()->action('ActionController@create', $group_id)
            ->withErrors($action->getErrors())
            ->withInput();
        }
        else
        {
            $action->save();
            return redirect()->action('ActionController@index', [$group->id]);
        }
    }



    /**
    * Display the specified resource.
    *
    * @param int $id
    *
    * @return Response
    */
    public function show(Group $group, Action $action)
    {
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
    public function edit(Request $request, Group $group, Action $action)
    {
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
    public function update(Request $request, Group $group, Action $action)
    {
        $action->name = $request->input('name');
        $action->body = $request->input('body');
        $action->start = Carbon::createFromFormat('Y-m-d H:i', $request->input('start'));
        $action->stop = Carbon::createFromFormat('Y-m-d H:i', $request->input('stop'));


        if ($action->location <> $request->input('location'))
        {

            // we need to update user address and geocode it
            $action->location = $request->input('location');
            if (!$action->geocode())
            {
                flash()->error(trans('messages.address_cannot_be_geocoded'));

            }
            else
            {
                flash()->info( trans('messages.ressource_geocoded_successfully'));
            }
        }

        //$action->user()->associate($request->user()); // we use revisionable to manage who changed what, so we keep the original author

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
    * @return \Illuminate\Http\Response
    */
    public function destroyConfirm(Request $request, Group $group, Action $action)
    {

        if (Gate::allows('delete', $action))
        {
            return view('actions.delete')
            ->with('action', $action)
            ->with('group', $group)
            ->with('tab', 'discussion');
        }
        else
        {
            abort(403);
        }

    }



    /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    *
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Group $group, Action $action)
    {
        if (Gate::allows('delete', $action))
        {
            $action->delete();
            flash()->success(trans('messages.ressource_deleted_successfully'));
            return redirect()->action('ActionController@index', [$group->id]);
        }
        else
        {
            abort(403);
        }
    }



    /**
    * Show the revision history of the discussion
    */
    public function history(Group $group, Action $action)
    {
        return view('actions.history')
        ->with('group', $group)
        ->with('action', $action)
        ->with('tab', 'action');
    }


}
