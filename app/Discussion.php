<?php

namespace App;

use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

class Discussion extends Model
{
    use RevisionableTrait;
    use ValidatingTrait;
    use SoftDeletes;
    use Taggable;
    use SearchableTrait;

    protected $rules = [
    'name'     => 'required',
    'body'     => 'required',
    'user_id'  => 'required|exists:users,id',
    'group_id' => 'required|exists:groups,id',
    ];

    protected $keepRevisionOf = ['name', 'body'];

    protected $table = 'discussions';
    protected $fillable = ['name', 'body', 'group_id'];

    public $timestamps = true;

    public $unreadcounter;

    public $read_comments;

    protected $dates = ['deleted_at'];

    protected $casts = ['user_id' => 'integer'];

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
            'discussions.name'    => 10,
            'discussions.body'    => 10,
        ],
    ];

    public function unReadCount()
    {
        if (\Auth::guest()) {
            return 0;
        }

        if ($this->userReadDiscussion->count() > 0) {
            return $this->total_comments - $this->userReadDiscussion->first()->read_comments;
        }

        return $this->total_comments;
    }

    public function group()
    {
        return $this->belongsTo(\App\Group::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    public function userReadDiscussion()
    {
        if (\Auth::check()) {
            return $this->hasMany(\App\UserReadDiscussion::class)->where('user_id', '=', \Auth::user()->id);
        } else {
            abort(500, 'Need to be logged in to access this userReadDiscussion relation');
        }
    }

    public function link()
    {
        return route('groups.discussions.show', [$this->group, $this]);
    }
}
