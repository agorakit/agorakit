<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * This controller is used for quick tag editing on various models (discussions & files curently).
 */
class TagController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $group, $type, $id)
    {
        if ($type == 'discussions') {
            $model = \App\Discussion::findOrFail($id);

            return view('tags.edit')
            ->with('name', $model->name)
            ->with('group', $model->group)
            ->with('model', $model)
            ->with('type', $type)
            ->with('id', $id)
            ->with('model_tags', $model->tags)
            ->with('all_tags', $model->group->tagsUsed());
        }

        if ($type == 'files') {
            $model = \App\File::findOrFail($id);

            return view('tags.edit')
            ->with('name', $model->name)
            ->with('group', $model->group)
            ->with('model', $model)
            ->with('type', $type)
            ->with('id', $id)
            ->with('model_tags', $model->tags)
            ->with('all_tags', $model->group->tagsUsed());
        }

        abort(404, 'Unknown type');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $group, $type, $id)
    {
        if ($type == 'discussions') {
            $model = \App\Discussion::findOrFail($id);
            $model->tag($request->get('tags'));
            flash(trans('messages.ressource_created_successfully'));

            return redirect()->route('groups.discussions.show', [$model->group, $model]);
        }

        if ($type == 'files') {
            $model = \App\File::findOrFail($id);
            $model->tag($request->get('tags'));
            flash(trans('messages.ressource_created_successfully'));

            return redirect()->route('groups.files.show', [$model->group, $model]);
        }

        abort(404, 'Unknown type');
    }
}
