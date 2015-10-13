<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class GroupUserController extends Controller {



  public function join(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    // this is solution 2, cleaner than solution 1 below
    $membership = \App\GroupUser::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    $membership->membership = 2;
    $membership->save();
    return redirect()->back();

    /*
    // load membership for this group and user combination (if it exists)
    // solution 1 :
    $membership = \App\GroupUser::where('user_id',  $request->user()->id)->where('group_id', $group_id)->first();

    if (!is_null ($membership))
    {
      // user has already some membership with this group, let's update it.
      $membership->membership = 2;
      $membership->save();
      return redirect()->back();
    }
    else
    {
        $group->users()->attach($request->user());
        return redirect()->back();
    }
    */


  }


  public function leave(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    // this is solution 2, cleaner than solution 1 below
    $membership = \App\GroupUser::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    $membership->membership = -1;
    $membership->save();
    return redirect()->back();

  }

  public function subscribe(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    // this is solution 2, cleaner than solution 1 below
    $membership = \App\GroupUser::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    $membership->membership = 1;
    $membership->save();
    return redirect()->back();

  }

  public function unsubscribe(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    // this is solution 2, cleaner than solution 1 below
    $membership = \App\GroupUser::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    $membership->membership = 0;
    $membership->save();
    return redirect()->back();
  }


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {

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
