<?php

namespace App;

use App\Traits\HasStatus;
use App\Traits\HasControlledTags;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

use App\User;
use App\UserReadDiscussion;
use Auth;
use Carbon\Carbon;

class Discussion extends Model
{
    use RevisionableTrait;
    use ValidatingTrait;
    use SoftDeletes;
    use Taggable;
    use SearchableTrait;
    use HasStatus;
    use HasControlledTags;

    protected $rules = [
        'name'     => 'required',
        'body'     => 'required',
        'user_id'  => 'required|exists:users,id',
        'group_id' => 'required|exists:groups,id',
    ];

    protected $fillable = ['name', 'body', 'group_id', 'user_id'];

    protected $keepRevisionOf = ['name', 'body', 'status'];
    protected $table = 'discussions';
    public $timestamps = true;
    public $unreadcounter;
    public $read_comments;

    protected $casts = [
        'user_id' => 'integer',
        'deleted_at' => 'datetime'
    ];

    public $modelName = 'discussion';

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

    public function getType()
    {
        return 'discussion';
    }

    /**
     * Unread count of comments for the current user
     */
    public function unReadCount()
    {
        if (Auth::guest()) {
            return 0;
        }

        $userReadDiscussion = $this->userReadDiscussion->first();

        if ($userReadDiscussion) {
            return $this->comments->count() - $userReadDiscussion->read_comments + 1;
        }

        return $this->comments->count() + 1;
    }

    /**
     * Mark this discussion as read for the current user
     */
    public function markAsRead()
    {
        $userReadDiscussion = UserReadDiscussion::firstOrNew(['user_id' => Auth::user()->id, 'discussion_id' => $this->id]);
        $userReadDiscussion->read_comments = $this->comments->count() + 1;
        $userReadDiscussion->read_at = Carbon::now();
        return $userReadDiscussion->save();
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Group::class)->withTrashed();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    public function userReadDiscussion(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        if (\Auth::check()) {
            return $this->hasMany(\App\UserReadDiscussion::class)->where('user_id', '=', \Auth::user()->id);
        } else {
            return false;
        }
    }

    public function link()
    {
        return route('groups.discussions.show', [$this->group, $this]);
    }


    /**
     * Returns the inbox email of this discussion (if it has one).
     * A discussion has an inbox if INBOX_DRIVER is not null in .env
     */
    public function inbox()
    {
        if (config('agorakit.inbox_driver')) {
            return config('agorakit.inbox_prefix') . 'reply-' . $this->id . config('agorakit.inbox_suffix');
        } else {
            return false;
        }
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }
}
