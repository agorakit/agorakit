<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;

class GroupPermissionController extends Controller
{
    /**
    * Display a listing of permissions in the specified group + admin ui to edit permissions
    *
    * @return Response
    */
    public function index(Request $request, Group $group)
    {
        $this->authorize('administer', $group);

        //$permissions = $group->getSetting('permissions');

        $permissions = ['member' => ['create-discussion', 'create-action', 'create-file', 'invite']];

        return view('permissions.index')
        ->with('permissions', $permissions)
        ->with('group', $group)
        ->with('tab', 'admin');
    }


}
