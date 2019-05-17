<?php

namespace App\Http\Controllers;

use App\Action;
use App\Group;
use Illuminate\Http\Request;
use URL;

/**
 * This controller allow users to attend or not to events.
 */
class ActionUserController extends Controller
{
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
    public function create(Request $request, Group $group, Action $action)
    {
        session()->put('url.intended', URL::previous());

        return view('actions.attend')
        ->with('group', $group)
        ->with('action', $action);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group, Action $action)
    {
        $rsvp = \App\ActionUser::firstOrNew(['user_id' => \Auth::user()->id, 'action_id' => $action->id]);
        $rsvp->save();
        flash(trans('messages.ressource_updated_successfully'));

        return redirect()->intended(route('groups.actions.show', [$group, $action]));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        session()->put('url.intended', URL::previous());

        return view('actions.unattend')
        ->with('group', $group)
        ->with('action', $action);
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
        $action->users()->detach(\Auth::user());
        flash(trans('messages.ressource_updated_successfully'));

        return redirect()->intended(route('groups.actions.show', [$group, $action]));
    }
}
