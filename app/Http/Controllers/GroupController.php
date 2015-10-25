<?php

namespace App\Http\Controllers;

use App\Group;
use Auth;
use Illuminate\Http\Request;

class GroupController extends Controller {

  public function __construct()
  {
    $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    $this->middleware('group.member', ['only' => ['edit', 'update', 'destroy']]);
    $this->middleware('cacheforanonymous', ['only' => ['index', 'show']]);
  }

  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index()
  {
    $groups = Group::with('membership')->orderBy('updated_at', 'desc')->paginate(12);

    //dd($groups);
    return view('groups.index')
    ->with('groups', $groups);
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return Response
  */
  public function create()
  {
    return view('groups.create');
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



   if ( $group->isInvalid())
   {
  // Oops.
    return redirect()->action('groupController@create')
      ->withErrors($group->getErrors())
      ->withInput();
   }

   $group->save();

   // make the current user a member of the group
   $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group->id]);
   $membership->membership = 2;
   $membership->save();

   return redirect()->action('GroupController@show', [$group->id]);
  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function show($id)
  {
    $group = Group::findOrFail($id);

    $discussions = $group->discussions()->orderBy('updated_at', 'desc')->limit(5)->get();
    $files = $group->files()->orderBy('updated_at', 'desc')->limit(5)->get();


    return view ('groups.show')
    ->with('group', $group)
    ->with('discussions', $discussions)
    ->with('files', $files)
    ->with('tab', 'home');


  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function edit($id)
  {

  }

  /**
  * Update the specified resource in storage.
  *
  * @param  int  $id
  * @return Response
  */
  public function update($id)
  {

  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return Response
  */
  public function destroy($id)
  {

  }

}
