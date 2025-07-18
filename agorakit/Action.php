<?php

namespace Agorakit;

use Agorakit\User;
use Agorakit\Group;
use Agorakit\Traits\HasControlledTags;
use Agorakit\Traits\HasVisibility;
use Agorakit\Traits\HasCover;
use Agorakit\Traits\HasLocation;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

class Action extends Model
{
    use ValidatingTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Taggable;
    use SearchableTrait;
    use hasLocation;
    use HasControlledTags;
    use HasVisibility;
    use HasCover;


    protected $rules = [
        'name'     => 'required',
        'user_id'  => 'required|exists:users,id',
        'group_id' => 'required|exists:groups,id',
        'start'    => 'required',
        'stop'     => 'required',
    ];

    protected $with = ['attending', 'notAttending']; // always load participants with actions

    protected $table = 'actions';
    public $timestamps = true;
    protected $casts = [
        'user_id' => 'integer',
        'deleted_at' => 'datetime',
        'start' => 'datetime',
        'stop' => 'datetime'
    ];

    protected $keepRevisionOf = ['name', 'start', 'stop', 'body', 'location'];

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
            'actions.name'    => 10,
            'actions.body'    => 10,
            'actions.location' => 2,
        ],
    ];

    public function getType()
    {
        return 'action';
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Group::class)->withTrashed();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function link()
    {
        return route('groups.actions.show', [$this->group, $this]);
    }

    /**
     * The users attending (or not) this action.
     */
    public function participation()
    {
        return $this->belongsToMany(User::class)->withPivot('status', 'notification');
    }


    /**
     * The users attending this action.
     */
    public function attending()
    {
        return $this->belongsToMany(User::class)->wherePivot('status', '10');
    }

    /**
     * The users NOT attending this action.
     */
    public function notAttending()
    {
        return $this->belongsToMany(User::class)->wherePivot('status', '-10');
    }

    /**
     * The users MAYBE attending this action.
     */
    public function maybeAttending()
    {
        return $this->belongsToMany(User::class)->wherePivot('status', '0');
    }

}
