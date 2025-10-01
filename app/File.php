<?php

namespace App;

use App\Traits\HasStatus;
use App\Traits\HasCover;
use App\Traits\HasControlledTags;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Storage;
use Carbon\Carbon;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

class File extends Model
{
    use ValidatingTrait;
    use SoftDeletes;
    use RevisionableTrait;
    use Taggable;
    use SearchableTrait;
    use HasStatus;
    use HasControlledTags;
    use HasCover;

    protected $rules = [
        'name'     => 'required',
        'user_id'  => 'required|exists:users,id',
        'group_id' => 'required|exists:groups,id',
    ];

    protected $table = 'files';
    public $timestamps = true;
    protected $casts = [
        'user_id' => 'integer',
        'deleted_at' => 'datetime'
    ];

    protected $keepRevisionOf = ['name', 'path', 'filesize', 'status'];

    // Item type can be :
    // 0 : file (stored on the server)
    // 1 : folder (virtual folders)
    // 2 : link (to an etherpad or google doc for instance)

    const FILE = 0;
    const FOLDER = 1;
    const LINK = 2;


    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /*
        * Columns and their priority in search results.
        * Columns with higher values are more important.
        * Columns with equal values have equal importance.
        *
        * @var array
        */
        'columns' => [
            'files.name'    => 10,
        ],
    ];


    public function getType()
    {
        return 'file';
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Group::class)->withTrashed();
    }

    public function link()
    {
        return route('groups.files.download', [$this->group, $this]);
    }

    /**
     * Returns the parent if it exists
     */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(File::class, 'parent_id');
    }

    /**
     * Returns all the parents as a collection of App\File
     */
    public function parents($includemyself = false)
    {
        $parents = collect();

        if ($includemyself) {
            $parents->push($this);
        }

        if ($this->parent) {
            $parent = $this->parent;

            // max parent depth is 10 // code is ugly but at least it's not recursive so it stops after 10 whatever happens // need to add error checking
            for ($i = 0; $i < 10; $i++) {
                $parents->push($parent);
                if ($parent->parent) {
                    $parent = $parent->parent;
                } else {
                    break;
                }
            }

            return $parents;
        }

        return $parents;
    }

    /**
     * Sets the parent of the file. Validates the parent before saving
     * Never set parent_id directly, use this function instead
     * Use setParent(null) to move to root
     */
    public function setParent(File $parent = null)
    {
        // handle case where parent is false : we move the file to root
        if (is_null($parent)) {
            $this->parent_id = null;
            $this->save();
            return $this;
        }

        // Validate parent :  not self, is a folder, exists, is in same group
        if ($parent->group_id <> $this->group_id) {
            // TODO throw error instead
            abort(500, 'Trying to set parent on a file from a different group or no group defined');
        }

        if ($parent->id == $this->id) {
            // TODO throw error instead
            abort(500, 'Cannot set parent to myself');
        }

        if (!$parent->isFolder()) {
            // TODO throw error instead
            abort(500, 'Parent must be a folder');
        }


        $this->parent_id = $parent->id;
        $this->save();
        return $this;
    }

    public function children()
    {
        return $this->group->files()->where('parent_id', $this->id);
    }

    public function hasChildren()
    {
        if ($this->children->count() > 0) {
            return true;
        }

        return false;
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
        if (in_array($this->mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
            return true;
        }
        return false;
    }

    /**
     * Returns the correct icon name depending on mimetype
     */
    public function icon()
    {
        if ($this->isImage()) {
            return 'image';
        }

        if ($this->isLink()) {
            return 'link';
        }

        $mimes = [
            'application/pdf'                                                            => 'pdf',
            'application/msword'                                                         => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'    => 'doc',
            'application/vnd.ms-powerpoint'                                              => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'  => 'ppt',
            'application/zip'                                                            => 'zip',
            'audio/mpeg'                                                                 => 'mp3',
            'video/mpeg'                                                                 => 'mp4',
            'application/vnd.oasis.opendocument.text'                                    => 'odt',
        ];

        // we return 'txt' if unknown
        return array_get($mimes, $this->mime, 'txt');
    }

    /**
     * Permanently delete this file and all versions  from storage.
     */
    public function deleteFromStorage()
    {
        try {
            // delete current file version
            if (Storage::exists($this->path)) {
                Storage::delete($this->path);
            }

            // delete version directory
            $storage_path = 'groups/' . $this->group->id . '/files/' . $this->id;
            if (Storage::exists($storage_path)) {
                Storage::deleteDirectory($storage_path);
            }
        } catch (\Exception $e) {
            return false;
        }

        return $this;
    }




    /**
     * Set file content from a file request -> to storage
     * You need to pass an uploaded file from a $request as $uploaded_file
     * The file you are attaching to must already exist in the DB.
     *
     * Can be called multiple times, a new timestamped filename is generated each time.
     *
     * Files are stored in groups/[group-id]/files/[file-id]/[timestamp]-[filename]
     */
    public function addToStorage($uploaded_file)
    {
        if ($this->exists) {
            // generate filenames and path
            $storage_path = 'groups/' . $this->group->id . '/files/' . $this->id;

            // simplified filename
            $filename = str_slug(pathinfo($uploaded_file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $uploaded_file->guessExtension();

            // storage filename start with precise timestamp including millisecond to avoid race condition
            $storage_filename = Carbon::now()->format('Y-m-d_H-i-s-v') . '-' . $filename;


            $stored_path = $uploaded_file->storeAs($storage_path, $storage_filename);

            $this->path = $stored_path;
            $this->name = $filename;
            $this->original_filename = $uploaded_file->getClientOriginalName();
            $this->mime = $uploaded_file->getMimeType();
            $this->filesize = $uploaded_file->getSize();

            // save it again
            $this->save();

            return $stored_path;
        } else {
            abort(500, 'First save a file before addToStorage(), file does not exists yet in DB');

            return false;
        }
    }

    /**
     * Returns the complete storage path to this file
     */
    public function storagePath()
    {
        return storage_path('app/') . $this->path;
    }
}
