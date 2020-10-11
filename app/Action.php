<?php

namespace App;

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

    protected $fillable = ['id']; // needed for actions import

    protected $rules = [
        'name'     => 'required|min:5',
        'user_id'  => 'required|exists:users,id',
        'group_id' => 'required|exists:groups,id',
        'start'    => 'required',
        'stop'     => 'required',
    ];

    protected $with = ['users']; // always load participants with actions

    protected $table = 'actions';
    public $timestamps = true;
    protected $dates = ['deleted_at', 'start', 'stop'];
    protected $casts = ['user_id' => 'integer'];

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
        return $this->morphMany('Vote', 'votable');
    }

    public function link()
    {
        return route('groups.actions.show', [$this->group, $this]);
    }

    /**
     * The users attending this action.
     */
    public function users()
    {
        return $this->belongsToMany(\App\User::class);
        // TODO candidate for deletion ?
    }


    /**
     * The users attending this action.
     */
    public function attending()
    {
        return $this->belongsToMany(\App\User::class)->wherePivot('status', '10');
    }

    /**
     * The users NOT attending this action.
     */
    public function notAttending()
    {
        return $this->belongsToMany(\App\User::class)->wherePivot('status', '-10');
    }

    /**
     * Geocode the item
     * Returns true if it worked, false if it didn't.
     */
    public function geocode()
    {
        if ($this->location == '') {
            $this->latitude = 0;
            $this->longitude = 0;

            return true;
        }

        $geocode = geocode($this->location);

        if ($geocode) {
            $this->latitude = $geocode['latitude'];
            $this->longitude = $geocode['longitude'];

            return true;
        }

        return false;
    }
}
