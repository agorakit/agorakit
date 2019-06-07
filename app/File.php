<?php

namespace App;

use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;
use Storage;

class File extends Model
{
    use ValidatingTrait;
    use SoftDeletes;
    use RevisionableTrait;
    use Taggable;

    protected $rules = [
        'name'     => 'required',
        'user_id'  => 'required|exists:users,id',
        'group_id' => 'required|exists:groups,id',
    ];

    protected $table = 'files';
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $casts = ['user_id' => 'integer'];

    protected $keepRevisionOf = ['name', 'path', 'filesize'];

    // Item type can be :
    // 0 : file (stored on the server)
    // 1 : folder (virtual folders)
    // 2 : link (to an etherpad or google doc for instance)

    const FILE = 0;
    const FOLDER = 1;
    const LINK = 2;

    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    public function group()
    {
        return $this->belongsTo(\App\Group::class)->withTrashed();
    }

    public function link()
    {
        return route('groups.files.download', [$this->group, $this]);
    }

    public function isFile()
    {
        return $this->item_type == $this::FILE;
    }

    public function isFolder()
    {
        return $this->item_type == $this::FOLDER;
    }

    public function isLink()
    {
        return $this->item_type == $this::LINK;
    }

    public function isImage()
    {
        if (in_array($this->mime, ['image/jpeg', 'image/png', 'image/gif'])) {
            return true;
        }

        return false;
    }

    public function icon()
    {
        if ($this->isImage()) {
            return 'image';
        }

        if ($this->isLink()) {
            return 'link';
        }

        $mimes = [
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'doc',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'  => 'ppt',
            'application/zip'  => 'zip',
            'audio/mpeg' => 'mp3',
            'video/mpeg' => 'mp4',
            'application/vnd.oasis.opendocument.text' => 'odt',
        ];

        // we return 'txt' if unknown
        return array_get($mimes, $this->mime, 'txt');
    }


    /**
    * Permanently delete this file from storage
    */
    public function deleteFromStorage()
    {
        if (Storage::exists($this->path)) {
            return Storage::delete($this->path);
        }
        return false;
    }

    /**
    * Set file content from a file request -> to storage
    * You need to pass an uploaded file from a $request as $uploaded_file
    * The file you are attaching to must already exist in the DB
    */
    public function addToStorage($uploaded_file)
    {
        if ($this->exists)
        {
            // generate filenames and path
            $storage_path = 'groups/'.$this->group->id.'/files';

            // simplified filename
            $filename = $this->id.'-'.str_slug(pathinfo($uploaded_file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' .  $uploaded_file->guessExtension();

            $complete_path = $uploaded_file->storeAs($storage_path, $filename );


            $this->path = $complete_path;
            $this->name = pathinfo($uploaded_file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $uploaded_file->guessExtension();
            $this->original_filename = $uploaded_file->getClientOriginalName();
            $this->mime = $uploaded_file->getMimeType();
            $this->filesize = $uploaded_file->getClientSize();

            // save it again
            $this->save();

            return $complete_path;
        }
        else
        {
            abort(500, 'First save a file before addToStorage(), file does not exists yet in DB');
            return false;
        }
    }
}
