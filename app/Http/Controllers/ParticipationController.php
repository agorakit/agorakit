<?php

namespace App\Http\Controllers;

use App\Action;
use App\Group;
use Illuminate\Http\Request;
use URL;

/**
 * This controller allow users to attend or not to events.
 */
class ParticipationController extends Controller
{
    

    public function edit(Request $request, Group $group, Action $action)
    {
        session()->put('url.intended', URL::previous());

        $participation = \App\Participation::firstOrNew(['user_id' => $request->user()->id, 'action_id' => $action->id]);

        return view('participation.edit')
            ->with('participation', $participation);
    }

    public function update(Request $request, Group $group, Action $action)
    {
        
        $rsvp = \App\Participation::firstOrNew(['user_id' => $request->user()->id, 'action_id' => $action->id]);
        $rsvp->notification = $request->get('notification');
        $rsvp->status = $request->get('participation');
        $rsvp->save();
        flash(trans('messages.ressource_updated_successfully'));

        return redirect()->intended(route('groups.actions.show', [$group, $action]));
    }


}
