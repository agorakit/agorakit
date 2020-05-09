<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discussion;
use App\Action;
use App\File;

class TaggerController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request, $type, $id)
    {
        if ($type == 'discussions') {
            $model = Discussion::findOrFail($id);
        }

        if ($type == 'actions') {
            $model = Action::findOrFail($id);
        }

        if ($type == 'files') {
            $model = File::findOrFail($id);
        }


        $allowed_tags = $model->group->allowedTags();

        $used_tags = $model->tags;

        return view('tagger.index')
        ->with('type', $type)
        ->with('id', $id)
        ->with('model', $model)
        ->with('allowed_tags', $allowed_tags)
        ->with('return_to', url()->previous())
        ->with('used_tags', $used_tags);
    }

    public function tag(Request $request, $type, $id, $name)
    {

        if ($type == 'discussions') {
            $model = Discussion::findOrFail($id);
        }

        if ($type == 'actions') {
            $model = Action::findOrFail($id);
        }

        if ($type == 'files') {
            $model = File::findOrFail($id);
        }

        if ($model->hasTag($name)) {
            $model->unTag($name);
        }
        else {
            $model->tag($name);
        }

        return redirect()->route('tagger.index', [$type, $id]);
    }


    public function add(Request $request, $type, $id)
    {
        if ($type == 'discussions') {
            $model = Discussion::findOrFail($id);
        }

        if ($type == 'actions') {
            $model = Action::findOrFail($id);
        }

        if ($type == 'files') {
            $model = File::findOrFail($id);
        }

        $model->tag($request->input('name'));

        return redirect()->route('tagger.index', [$type, $id]);
    }


    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        //
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        //
    }
}
