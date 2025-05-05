<?php

namespace App;

use App\User;
use App\Group;
use App\Discussion;
use App\Traits\HasControlledTags;
use App\Traits\HasVisibility;
use App\Traits\HasCover;
use App\Traits\HasLocation;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;


class Event extends Model
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

    protected $with = ['attending', 'notAttending']; // always load participants with events

    protected $table = 'events';
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
            'events.name'    => 10,
            'events.body'    => 10,
            'events.location' => 2,
        ],
    ];

    public function getType()
    {
        return 'event';
    }

    public function discussion(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DIscussion::class)->withTrashed();
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
        return route('groups.events.show', [$this->group, $this]);
    }

    /**
     * The users attending (or not) this event.
     */
    public function participation()
    {
        return $this->belongsToMany(User::class)->withPivot('status', 'notification');
    }


    /**
     * The users attending this event.
     */
    public function attending()
    {
        return $this->belongsToMany(User::class)->wherePivot('status', '10');
    }

    /**
     * The users NOT attending this event.
     */
    public function notAttending()
    {
        return $this->belongsToMany(User::class)->wherePivot('status', '-10');
    }

    /**
     * The users MAYBE attending this event.
     */
    public function maybeAttending()
    {
        return $this->belongsToMany(User::class)->wherePivot('status', '0');
    }

    /**
     * Create associated discussion
     */
    public function linkDiscussion()
    {
        $discussion = new Discussion();
        $discussion->name = $this->name;
        $discussion->body = $this->name;
        $discussion->total_comments = 1; // the discussion itself is already a comment
        $discussion->user()->associate($this->user);

        if (!$this->group->discussions()->save($discussion)) {
            // Oops.
            return redirect()->route('groups.events.create', $this->group)
                ->withErrors($discussion->getErrors())
                ->withInput();
        }

        $this->discussion()->associate($discussion);
        $this->save();
    }

}
