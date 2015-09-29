<?php

namespace App\Http\Controllers;
use App\Group;
use Auth;

class DiscussionController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index($id)
  {
    $discussions = Group::findOrFail($id)->discussions();
    return view ('discussions.index')->with('discussions', $discussions);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {

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
    $discussion = Discussion::findOrFail($id);
    return view ('discussions.show')->with('discussion', $discussion);
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

?>
