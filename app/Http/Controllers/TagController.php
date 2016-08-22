<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Discussion;
use App\Group;
use Carbon\Carbon;
use DB;
use App\Helpers\QueryHelper;
use Gate;

use DraperStudio\Taggable\Models\Tag;

class TagController extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index(Request $request)
    {
        return view('tags.index')
        ->with('tags', $tags);
    }


    /**
    * Display the specified resource.
    *
    * @param  int  $id
    *
    * @return Response
    */
    public function show(Request $request, Tag $tag)
    {

        return view('tags.show')
        ->with('discussion', $discussion)
        ->with('read_comments', $read_comments)
        ->with('group', $group)
        ->with('tab', 'discussion');
    }


}
