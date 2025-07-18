<?php

namespace App\Http\Controllers;

use App\Action;
use App\Group;
use App\Participation;
use Illuminate\Http\Request;
use URL;

/**
 * This controller allow users to attend or not to events.
 */
class ParticipationController extends Controller
{


    public function edit(Request $request, Group $group, Action $action)
    {
        $this->authorize('participate', $action);

        session()->put('url.intended', URL::previous());

        $participation = Participation::firstOrNew(['user_id' => $request->user()->id, 'action_id' => $action->id]);

        return view('participation.edit')
            ->with('participation', $participation);
    }

    public function update(Request $request, Group $group, Action $action)
    {
        $this->authorize('participate', $action);

        $rsvp = Participation::firstOrNew(['user_id' => $request->user()->id, 'action_id' => $action->id]);
        $rsvp->notification = $request->get('notification');
        $rsvp->status = $request->get('participation');
        $rsvp->save();
        flash(trans('messages.ressource_updated_successfully'));

        return redirect()->intended(route('groups.actions.show', [$group, $action]));
    }

    /**
     * This one is called as get to quickly change partipation status
     */
    public function set(Request $request, Group $group, Action $action, $status)
    {
        $this->authorize('participate', $action);
        $rsvp = Participation::firstOrNew(['user_id' => $request->user()->id, 'action_id' => $action->id]);
        //$rsvp->notification = $request->get('notification');

        if ($status == 'yes') {
            $rsvp->status = Participation::PARTICIPATE;
        }
        elseif ($status == 'no') {
            $rsvp->status = Participation::WONT_PARTICIPATE;
        }
        elseif ($status == 'maybe') {
            $rsvp->status = Participation::UNDECIDED;
        }

        $rsvp->save();

        return redirect()->back();
    }
}
