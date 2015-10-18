<?php

namespace App\Http\Controllers;

use App\Group;
use Auth;

class GroupController extends Controller {

  public function __construct()
  {
    $this->middleware('auth', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    $this->middleware('group.member', ['only' => ['store', 'edit', 'update', 'destroy']]);
  }

  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index()
  {
    $groups = Group::paginate(12);

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
    return 'not yet'; // TODO
  }

  /**
  * Store a newly created resource in storage.
  *
  * @return Response
  */
  public function store()
  {

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
