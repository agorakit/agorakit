<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;
use App\File;
use App\Group;


/**
 * This is the integration with onlyoffice (this can change later).
 * Provide users the ability to edit a file using onlyoffice (documents, spreadsheets, presentations)
 */
class FileEditorController extends Controller
{

    /**
     * Display the specified resource.
     *
     */
    public function show(Request $request, Group $group, File $file)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid signature');
        }

        if (Storage::exists($file->path)) {
            return (new Response(Storage::get($file->path), 200))
                ->header('Content-Type', $file->mime);
            //->header('Content-Disposition', 'inline; filename="'.$file->original_filename.'"');
        } else {
            abort(404, 'File not found in storage at ' . $file->path);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(Request $request, Group $group, File $file)
    {
        $this->authorize('update', $file);


        return view('collaboration.edit')
            ->with('group', $group)
            ->with('file', $file);
    }
}
