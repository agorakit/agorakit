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

class AdminGroupController extends Controller
{
    public function __construct()
    {

    }



    public function settings(Request $request, Group $group)
    {
        return 'ok';
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
