<?php

namespace App\Http\Controllers;

use App\CalendarEvent;
use App\Group;
use App\Services\CalendarEventService;
use Auth;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

class GroupCalendarEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, Group $group)
    {
        $this->authorize('view-calendarevents', $group);

        $view = 'grid';

        if (Auth::check()) {
            if (Auth::user()->getPreference('calendar')) {
                if (Auth::user()->getPreference('calendar') == 'list') {
                    $view = 'list';
                } else {
                    $view = 'grid';
                }
            }

            if ($request->get('type') == 'list') {
                Auth::user()->setPreference('calendar', 'list');
                $view = 'list';
            }

            if ($request->get('type') == 'grid') {
                Auth::user()->setPreference('calendar', 'grid');
                $view = 'grid';
            }
        } else {
            if ($request->get('type') == 'list') {
                $view = 'list';
            }

            if ($request->get('type') == 'grid') {
                $view = 'grid';
            }
        }

        if ($view == 'list') {
            $events = $group->calendarevents()
                ->with('user', 'group', 'tags')
                ->orderBy('start', 'asc')
                ->where(function ($query) {
                    $query->where('start', '>', Carbon::now()->subDay())
                        ->orWhere('stop', '>', Carbon::now()->addDay());
                })
                ->paginate(10);

            return view('calendarevents.index')
                ->with('type', 'list')
                ->with('title', $group->name . ' - ' . trans('messages.calendar'))
                ->with('events', $events)
                ->with('group', $group)
                ->with('tab', 'calendarevent');
        }

        return view('calendarevents.index')
            ->with('type', 'grid')
            ->with('group', $group)
            ->with('tab', 'calendarevent');
    }

    public function indexJson(Request $request, Group $group)
    {
        $this->authorize('view-calendarevents', $group);

        // load of events between start and stop provided by calendar js
        if ($request->has('start') && $request->has('end')) {
            $events = $group->calendarevents()
                ->with('user', 'group', 'tags')
                ->where('start', '>', Carbon::parse($request->get('start'))->subMonth())
                ->where('stop', '<', Carbon::parse($request->get('end'))->addMonth())
                ->orderBy('start', 'asc')->get();
        } else {
            $events = $group->calendarevents()->orderBy('start', 'asc')->get();
        }

        return CalendarEventService::calendarEventsToFullCallendarJson($events);
    }


    /**
     * Prepare locations list for web menu
     */
    public function getListedLocations(Group $group)
    {
        $listed_locations = [];
        foreach (Auth::user()->groups as $user_group) {
            foreach ($user_group->getNamedLocations() as $key => $location) {
                if (!array_key_exists($key, $listed_locations)) {
                    if ($location->city) {
                        $listed_locations[$key] = $location->name . " (" . $location->city . ")";
                    } else {
                        $listed_locations[$key] = $location->name;
                    }
                }
            }
        }
        return $listed_locations;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request, Group $group)
    {
        if ($group->exists) {
            $this->authorize('create-calendarevent', $group);
        }

        // preload the event to duplicate if present in url to allow easy duplication of an existing event
        if ($request->has('duplicate')) {
            $event = CalendarEvent::findOrFail($request->get('duplicate'));
            $this->authorize('view', $event);
        } else {
            $event = new CalendarEvent();

            if ($request->get('start')) {
                $event->start = Carbon::parse($request->get('start'));
            } else {
                $event->start = Carbon::now();
            }

            if ($request->get('stop')) {
                $event->stop = Carbon::parse($request->get('stop'));
            }

            // handle the case where the event is exactly one (ore more) day duration : it's a full day event, remove one second

            if ($event->start->hour == 0 && $event->start->minute == 0 && $event->stop->hour == 0 && $event->stop->minute == 0) {
                $event->stop = Carbon::parse($request->get('stop'))->subSecond();
            }
        }

        if ($request->get('title')) {
            $event->name = $request->get('title');
        }

        if ($request->has('location')) {
            $event->location = $request->input('location');
        }

        $event->group()->associate($group);

        return view('calendarevents.create')
            ->with('event', $event)
            ->with('model', $event)
            ->with('group', $group)
            ->with('allowedTags', $event->getTagsInUse())
            ->with('newTagsAllowed', $event->areNewTagsAllowed())
            ->with('listedLocation', null)
            ->with('listedLocations', $this->getListedLocations($event->group))
            ->with('tab', 'calendarevent');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request, Group $group)
    {
        // if no group is in the route, it means user choose the group using the dropdown
        if (!$group->exists) {
            $group = Group::findOrFail($request->get('group'));
        }

        $this->authorize('create-calendarevent', $group);

        $event = new CalendarEvent();

        $event->name = $request->input('name');
        $event->body = $request->input('body');

        try {
            $event->start = Carbon::createFromFormat('Y-m-d H:i', $request->input('start_date') . ' ' . $request->input('start_time'));
        } catch (\Exception $e) {
            return redirect()->route('groups.calendarevents.create', $group)
                ->withErrors($e->getMessage() . '. Incorrect format in the start date, use yyyy-mm-dd hh:mm')
                ->withInput();
        }

        try {
            if ($request->get('stop_date')) {
                if ($request->get('stop_time')) {
                    $event->stop = Carbon::createFromFormat('Y-m-d H:i', $request->input('stop_date') . ' ' . $request->input('stop_time'));
                } else { // asssume event will stop on stop_date and start_time
                    $event->stop = Carbon::createFromFormat('Y-m-d H:i', $request->input('stop_date') . ' ' . $request->input('start_time'));
                }
            } else {
                if ($request->get('stop_time')) { // asssume event will stop on start_date and stop_time
                    $event->stop = Carbon::createFromFormat('Y-m-d H:i', $request->input('start_date') . ' ' . $request->input('stop_time'));
                } else { // asssume event has unknown duration and store start_date and start_time
                    $event->stop = Carbon::createFromFormat('Y-m-d H:i', $request->input('start_date') . ' ' . $request->input('start_time'));
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('groups.calendarevents.create', $group)
                ->withErrors($e->getMessage() . '. Incorrect format in the stop date, use yyyy-mm-dd hh:mm')
                ->withInput();
        }

        if ($event->start > $event->stop) {
            return redirect()->route('groups.calendarevents.create', $group)
                ->withErrors(__('Start date cannot be after end date'))
                ->withInput();
        }

        if ($request->has('listed_location')) {
            foreach ($this->getListedLocations($group) as $key => $location) {
                if ($key == $request->input('listed_location')) {
                    $event->location = $group->getNamedLocations()[$key];
                }
            }
        } elseif ($request->has('location')) {
            // Validate input
            try {
                $event->location = $request->input('location');
            } catch (\Exception $e) {
                return redirect()->route('groups.calendarevents.create', $group)
                    ->withErrors($e->getMessage() . '. Incorrect location')
                    ->withInput();
            }

            // Geocode
            if (!$event->geocode()) {
                flash(trans('messages.location_cannot_be_geocoded'));
            } else {
                flash(trans('messages.ressource_geocoded_successfully'));
            }
        }

        $event->user()->associate($request->user());

        // handle visibility
        if ($request->has('visibility')) {
            $event->makePublic();
        } else {
            $event->makePrivate();
        }

        if (!$group->calendarevents()->save($event)) {
            // Oops.
            return redirect()->route('groups.calendarevents.create', $group)
                ->withErrors($event->getErrors())
                ->withInput();
        }

        // handle tags
        if ($request->get('tags')) {
            $event->tag($request->get('tags'));
        }


        // handle cover
        if ($request->hasFile('cover')) {
            if ($event->setCoverFromRequest($request)) {
                flash(trans('Cover added successfully'));
            } else {
                warning(trans('Could not handle cover image. Is it an image file (png, jpeg,...  ?)'));
            }
        }



        // update activity timestamp on parent items
        $group->touch();
        Auth::user()->touch();

        flash(trans('messages.ressource_created_successfully'));



        // return to previous url if it is set in the create form
        if (request()->input('context') === 'group') {
            return redirect()->route('groups.calendarevents.index', $group);
        } else {
            return redirect()->route('calendar');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(Group $group, CalendarEvent $event)
    {
        $this->authorize('view', $event);

        return view('calendarevents.show')
            ->with('title', $group->name . ' - ' . $event->name)
            ->with('event', $event)
            ->with('model', $event)
            ->with('group', $group)
            ->with('tab', 'calendarevent');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, Group $group, CalendarEvent $event)
    {
        $this->authorize('update', $event);

        $listed_location = "other";
        foreach ($group->getNamedLocations() as $key => $location) {
            if ($event->location == $location) {
                $listed_location = $key;
            }
        }

        return view('calendarevents.edit')
            ->with('event', $event)
            ->with('model', $event)
            ->with('group', $group)
            ->with('allowedTags', $event->getAllowedTags())
            ->with('newTagsAllowed', $event->areNewTagsAllowed())
            ->with('selectedTags', $event->getSelectedTags())
            ->with('listedLocation', $listed_location)
            ->with('listedLocations', $this->getListedLocations($group))
            ->with('tab', 'calendarevent');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, Group $group, CalendarEvent $event)
    {
        $this->authorize('update', $event);

        $event->name = $request->input('name');
        $event->body = $request->input('body');

        $event->start = Carbon::createFromFormat('Y-m-d H:i', $request->input('start_date') . ' ' . $request->input('start_time'));

        if ($request->has('stop_date') && $request->get('stop_date') != '') {
            $event->stop = Carbon::createFromFormat('Y-m-d H:i', $request->input('stop_date') . ' ' . $request->input('stop_time'));
        } else {
            $event->stop = Carbon::createFromFormat('Y-m-d H:i', $request->input('start_date') . ' ' . $request->input('stop_time'));
        }

        // handle location
        $old_location = $event->location;
        $listed_location = $request->input('listed_location');
        if ($listed_location == 'other') {
            $listed_location = "";
        }
        if ($listed_location) {
            foreach ($this->getListedLocations($group) as $key => $location) {
                if ($key == $listed_location) {
                    $event->location = $group->getNamedLocations()[$key];
                }
            }
        } elseif ($request->has('location')) {
            // Validate input
            try {
                $event->location = $request->input('location');
            } catch (\Exception $e) {
                return redirect()->route('groups.calendarevents.create', $event)
                    ->withErrors($e->getMessage() . '. Incorrect location')
                    ->withInput();
            }
        }

        // Geocode
        if ($event->location <> $old_location) {
            if (!$event->geocode()) {
                flash(trans('messages.location_cannot_be_geocoded'));
            } else {
                flash(trans('messages.ressource_geocoded_successfully'));
            }
        }

        // handle tags
        if ($request->get('tags')) {
            $event->tag($request->get('tags'));
        } else {
            $event->detag();
        }

        // handle visibility
        if ($request->has('visibility')) {
            $event->makePublic();
        } else {
            $event->makePrivate();
        }

        // handle cover
        if ($request->hasFile('cover')) {
            if ($event->setCoverFromRequest($request)) {
                flash(trans('Cover image has been updated, please reload to see the new cover'));
            } else {
                flash(trans('Error adding a new cover'));
            }
        } else {
            flash('no cover');
        }

        if ($event->isInvalid()) {
            // Oops.
            return redirect()->route('groups.calendarevents.create', $group)
                ->withErrors($event->getErrors())
                ->withInput();
        }

        $event->save();

        return redirect()->route('groups.calendarevents.show', [$event->group, $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyConfirm(Request $request, Group $group, CalendarEvent $event)
    {
        $this->authorize('delete', $event);

        if (Gate::allows('delete', $event)) {
            return view('calendarevents.delete')
                ->with('event', $event)
                ->with('group', $group)
                ->with('tab', 'discussions');
        } else {
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
    public function destroy(Request $request, Group $group, CalendarEvent $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        flash(trans('messages.ressource_deleted_successfully'));

        return redirect()->route('groups.calendarevents.index', $group);
    }

    /**
     * Show the revision history of the discussion.
     */
    public function history(Group $group, CalendarEvent $event)
    {
        $this->authorize('history', $event);

        return view('calendarevents.history')
            ->with('group', $group)
            ->with('event', $event)
            ->with('tab', 'event');
    }
}
