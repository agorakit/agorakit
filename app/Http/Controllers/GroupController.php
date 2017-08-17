<?php

namespace App\Http\Controllers;

use App\Group;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Storage;
use File;
use Image;
use Gate;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('member', ['only' => ['edit', 'update', 'destroy']]);
        $this->middleware('cache', ['only' => ['cover']]);
    }



    /**
    * Display the specified resource.
    *
    * @param  int  $id
    *
    * @return Response
    */
    public function show(Group $group)
    {

        if (Auth::check()) {
            $discussions = $group->discussions()
            ->has('user')
            ->with('user', 'group', 'userReadDiscussion')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        } else {
            $discussions = $group->discussions()
            ->has('user')
            ->with('user', 'group')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        }
        $files = $group->files()->with('user')->orderBy('updated_at', 'desc')->limit(5)->get();

        $actions = $group->actions()->where('start', '>=', Carbon::now())->orderBy('start', 'asc')->limit(10)->get();

        return view('groups.show')
        ->with('group', $group)
        ->with('discussions', $discussions)
        ->with('actions', $actions)
        ->with('files', $files)
        ->with('tab', 'home');
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function create()
    {
        return view('groups.create')
        ->with('all_tags', \App\Group::allTags());
    }

    /**
    * Store a newly created resource in storage.
    *
    * @return Response
    */
    public function store(Request $request)
    {
        $group = new group();

        $group->name = $request->input('name');
        $group->body = $request->input('body');
        $group->group_type = $request->input('group_type');

        if ($request->get('address'))
        {
            $group->address = $request->input('address');
            if (!$group->geocode())
            {
                flash()->error(trans('messages.address_cannot_be_geocoded'));
            }
            else
            {
                flash()->info( trans('messages.ressource_geocoded_successfully'));
            }
        }

        $group->user()->associate(Auth::user());
        $group->tag($request->get('tags'));


        if ($group->isInvalid()) {
            // Oops.
            return redirect()->action('GroupController@create')
            ->withErrors($group->getErrors())
            ->withInput();
        }
        $group->save();

        // handle cover
        if ($request->hasFile('cover'))
        {
            Storage::disk('local')->makeDirectory('groups/' . $group->id);
            Image::make($request->file('cover'))->widen(800)->save(storage_path() . '/app/groups/' . $group->id . '/cover.jpg');
            Image::make($request->file('cover'))->fit(300,200)->save(storage_path() . '/app/groups/' . $group->id . '/thumbnail.jpg');
        }

        // make the current user an admin of the group
        $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group->id]);
        $membership->notification_interval = 60 * 24; // default to daily interval
        $membership->membership = \App\Membership::ADMIN;
        $membership->save();

        return redirect()->action('MembershipController@settings', [$group->id]);
    }



    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    *
    * @return Response
    */
    public function edit(Request $request, Group $group)
    {
        return view('groups.edit')
        ->with('group', $group)
        ->with('all_tags', \App\Group::allTags())
        ->with('model_tags', $group->tags)
        ->with('tab', 'home');
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    *
    * @return Response
    */
    public function update(Request $request, Group $group)
    {

        $group->name = $request->input('name');
        $group->body = $request->input('body');

        if (Gate::allows('changeGroupType', $group))
        {
            $group->group_type = $request->input('group_type');
        }


        if ($group->address <> $request->input('address'))
        {
            // we need to update user address and geocode it
            $group->address = $request->input('address');
            if (!$group->geocode())
            {
                flash()->error(trans('messages.address_cannot_be_geocoded'));
            }
            else
            {
                flash()->info( trans('messages.ressource_geocoded_successfully'));
            }
        }


        $group->user()->associate(Auth::user());
        $group->retag($request->get('tags'));

        // validation
        if ($group->isInvalid()) {
            // Oops.
            return redirect()->action('GroupController@edit', $group->id)
            ->withErrors($group->getErrors())
            ->withInput();
        }

        // handle cover
        if ($request->hasFile('cover'))
        {
            Storage::disk('local')->makeDirectory('groups/' . $group->id);
            Image::make($request->file('cover'))->widen(800)->save(storage_path() . '/app/groups/' . $group->id . '/cover.jpg');
            Image::make($request->file('cover'))->fit(300,200)->save(storage_path() . '/app/groups/' . $group->id . '/thumbnail.jpg');
        }

        $group->save();

        flash()->info(trans('messages.ressource_updated_successfully'));

        return redirect()->action('GroupController@show', [$group->id]);
    }


    public function cover(Group $group)
    {
        $path = storage_path() . '/app/groups/' . $group->id . '/cover.jpg';

        if (File::exists($path))
        {
            $cachedImage = Image::cache(function($img) use ($path) {
                return $img->make($path)->fit(600, 350);
            }, 60000, true);

            return $cachedImage->response();

        }
        else
        {
            return Image::canvas(600,350)->fill('#cccccc')->response(); // TODO caching or default group image instead
        }
    }


    public function avatar(Group $group)
    {
        $path = storage_path() . '/app/groups/' . $group->id . '/cover.jpg';

        if (File::exists($path))
        {
            $cachedImage = Image::cache(function($img) use ($path) {
                return $img->make($path)->fit(32, 32);
            }, 60000, true);

            return $cachedImage->response();

        }
        else
        {
            return Image::canvas(600,350)->fill('#cccccc')->response(); // TODO caching or default group image instead
        }
    }





    /**
    * Show the revision history of the group
    */
    public function history(Group $group)
    {
        return view('groups.history')
        ->with('group', $group)
        ->with('tab', 'home');
    }


    public function destroyConfirm(Request $request, Group $group)
    {
        if (Gate::allows('delete', $group))
        {
            return view('groups.delete')
            ->with('group', $group)
            ->with('tab', 'home');
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
    public function destroy(Request $request, Group $group)
    {
        if (Gate::allows('delete', $group))
        {
            $group->delete();
            flash()->info(trans('messages.ressource_deleted_successfully'));
            return redirect()->action('DashboardController@index');
        }
        else
        {
            abort(403);
        }
    }
}
