<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class MembershipController extends Controller {



  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings
  *
  */
  public function join(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.join')
    ->with('group', $group)
    ->with('membership', $membership);

  }


  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings
  *
  */
  public function leave(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.leave')
    ->with('group', $group)
    ->with('membership', $membership);

  }


  /**
  * Show a settings screen for a specific group. Allows a user to join, leave, set subscribe settings
  *
  */
  public function settings(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    return view('membership.edit')
    ->with('group', $group)
    ->with('membership', $membership);

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
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request, $group_id)
  {
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::firstOrNew(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    $membership->membership = 2;
    $membership->save();
    return redirect()->action('GroupController@show', [$group->id])->with('message', 'Welcome to this group');

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
    $group = \App\Group::findOrFail($group_id);

    // load or create membership for this group and user combination
    $membership = \App\Membership::findOrFail(['user_id' => $request->user()->id, 'group_id' => $group_id]);

    $membership->membership = 2; // TODO
    $membership->save();
    return redirect()->action('GroupController@show', [$group->id])->with('message', 'Your settings have been updated');

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request, $group_id)
  {

    // load or create membership for this group and user combination
    $membership = \App\Membership::findOrFail(['user_id' => $request->user()->id, 'group_id' => $group_id]);
    $membership->membership = -1;
    $membership->save();
    return redirect()->action('GroupController@index');
  }

}

?>
