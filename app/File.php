<?php

namespace App;

use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;
use App\Traits\LogsActivity;

class File extends Model
{
    use ValidatingTrait;
    use SoftDeletes;
    use RevisionableTrait;
    use Taggable;
    use LogsActivity;

    protected $touches = ['group', 'user'];

    protected $rules = [
        'name'     => 'required',
        'user_id'  => 'required|exists:users,id',
        'group_id' => 'required|exists:groups,id',
    ];

    protected $table = 'files';
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $casts = ['user_id' => 'integer'];

    // Item type can be :
    // 0 : file (stored on the server)
    // 1 : folder (virtual folders)
    // 2 : link (to an etherpad or google doc for instance)

    const FILE = 0;
    const FOLDER = 1;
    const LINK = 2;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function link()
    {
        return action('FileController@download', [$this->group, $this]);
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


}
