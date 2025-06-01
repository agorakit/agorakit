<?php

namespace App;

use App\Traits\HasStatus;
use App\Traits\HasControlledTags;
use App\Traits\HasCover;
use App\Traits\HasLocation;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Storage;
use ZipArchive;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;
use Carbon\Carbon;
use App\File;
use App\Action;
use App\Invite;
use App\Activity;

class Group extends Model
{
    use ValidatingTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Taggable;
    use Sluggable;
    use SearchableTrait;
    use HasStatus;
    use hasCover;
    use hasLocation;
    use HasControlledTags;

    protected $rules = [
        'name' => 'required',
        'body' => 'required',
    ];

    protected $fillable = ['id', 'name', 'body', 'cover'];
    protected $casts = ['user_id' => 'integer', 'settings' => 'array'];

    protected $keepRevisionOf = ['name', 'body', 'cover', 'color', 'group_type', 'location', 'settings', 'status'];

    /**** various group types ****/
    // open group, default
    const OPEN = 0;
    const CLOSED = 1;
    const SECRET = 2;


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
            'groups.name'    => 10,
            'groups.body'    => 10,
            'groups.location' => 2,
        ],
    ];

    public $type = 'group';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'reserved' => ['reply', 'reply-', 'admin-'],
                'unique' => true,
            ],
        ];
    }

    public function getType()
    {
        return 'group';
    }


    /**
     * Returns the css color (yes) of this group. Curently random generated.
     */
    public function color()
    {
        if ($this->color) {
            return $this->color;
        } else {
            $this->color = 'rgb(' . rand(0, 200) . ' , ' . rand(0, 200) . ' , ' . rand(0, 200) . ')';
            $this->save();

            return $this->color;
        }
    }

    /**
     * Returns all the users of this group.
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\User::class, 'membership')->where('membership', '>=', \App\Membership::MEMBER)->withTimestamps()->withPivot('membership');
    }

    /**
     * Returns all the admins of this group.
     */
    public function admins(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\User::class, 'membership')->where('membership', \App\Membership::ADMIN)->withTimestamps()->withPivot('membership');
    }

    /**
     * Returns all the candidates of this group.
     */
    public function candidates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Membership::class)->where('membership', \App\Membership::CANDIDATE);
    }

    /**
     * The user who created or updated this group title and description.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    /**
     * return membership for the current user.
     */
    public function membership(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        if (\Auth::check()) {
            return $this->belongsToMany(\App\User::class, 'membership')
                ->where('user_id', '=', \Auth::user()->id)
                ->withPivot('membership');
        } else {
            return $this->belongsToMany(\App\User::class, 'membership')
                ->withPivot('membership');
        }
    }

    public function memberships(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Membership::class);
    }

    /**
     * Returns all the discussions belonging to this group.
     */
    public function discussions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Discussion::class);
    }

    /**
     * Returns all the actions belonging to this group.
     */
    public function actions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Action::class);
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class);
    }

    public function folders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class)->where('item_type', File::FOLDER);
    }

    public function invites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invite::class);
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Activity::class)->orderBy('created_at', 'desc');
    }

    /**
     *   Returns true if current user is a member of this group.
     */
    public function isMember()
    {
        if (\Auth::check()) {
            $member = $this->membership->first();
            if ($member && $member->pivot->membership >= \App\Membership::MEMBER) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns membership info for curently logged user
     * Returns false if no membership found.
     */
    public function getMembership()
    {
        if (\Auth::check()) {
            $member = $this->membership->first();

            if ($member) {
                return $member->pivot->membership;
            }
        }

        return false;
    }

    /** constructs a links to the group **/
    public function link()
    {
        return route('groups.show', $this);
    }

    /**
     * Returns the inbox email of this group (if it has one).
     * A group has an inbox if INBOX_DRIVER is not null in .env
     */
    public function inbox()
    {
        if (config('agorakit.inbox_driver')) {
            return config('agorakit.inbox_prefix') . $this->slug . config('agorakit.inbox_suffix');
        } else {
            return false;
        }
    }


    /** returns true if the group is open (joinable by all) **/
    public function isOpen()
    {
        if ($this->group_type == $this::OPEN) {
            return true;
        } else {
            return false;
        }
    }

    /** returns true if the group is closed (invite/ask to join only) **/
    public function isClosed()
    {
        if ($this->group_type == $this::CLOSED) {
            return true;
        } else {
            return false;
        }
    }

    /** returns true if the group is secret (hidden, invite only) **/
    public function isSecret()
    {
        if ($this->group_type == $this::SECRET) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Scope a query to only include public groups.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('group_type', $this::OPEN);
    }

    /**
     * Scope a query to only include open groups.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('group_type', $this::OPEN);
    }

    /**
     * Scope a query to only include closed groups.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query)
    {
        return $query->where('group_type', $this::CLOSED);
    }

    /**
     * Scope a query to only include secret groups.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSecret($query)
    {
        return $query->where('group_type', $this::SECRET);
    }

    /**
     * Scope a query to exclude secret groups.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotSecret($query)
    {
        return $query->where('group_type', '!=', $this::SECRET);
    }

    public function scopeActive($query)
    {
        return $query->where('updated_at', '>',  Carbon::now()->subMonths(3)->toDateTimeString());
    }


    /**
     * Returns the current setting $key for the group, $default if not set.
     */
    public function getSetting($key, $default = false)
    {
        $settings = $this->settings;
        if (isset($settings[$key])) {
            return $settings[$key];
        } else {
            return $default;
        }
    }

    /**
     * Set the setting $key to $value for the group
     * No validation is made on this layer, settings are stored in the json text field of the DB.
     */
    public function setSetting($key, $value)
    {
        $settings = $this->settings;
        $settings[$key] = $value;
        $this->settings = $settings;

        return $this->save();
    }

    /**
     * Get named locations in the group
     */
    public function getNamedLocations()
    {
        $arr = [];
        if ($this->location->name) {
            $arr[$this->location->name] = $this->location;
        }
        foreach ($this->actions()->get() as $action) {
            if ($action->location->name) {
                $key = $action->location->name . $action->location->city;
                $arr[$key] = $action->location;
            }
        }
        ksort($arr);

        return $arr;
    }

    /**
     * Export group data
     */
    public function export()
    {
        // load related content. I know it cascades but this way I have a complete list of models I need to process
        $this->load([
            'user',
            'memberships.user',
            'actions',
            'actions.user',
            'discussions',
            'discussions.user',
            'discussions.comments',
            'discussions.comments.user',
            'discussions.comments.reactions',
            'discussions.comments.reactions.user',
            'discussions.reactions',
            'discussions.reactions.user',
            'files',
            'files.user',
            'tags'
        ]);

        // group storage root path
        $root = 'groups/' . $this->id . '/';

        // save group json to storage
        Storage::put($root . 'group.json', $this->toJson());

        flash('Json export has been put into ' . $root . 'group.json');

        // create a zip file with the whole group folder
        $zipdir = Storage::disk('tmp')->url('');
        $zipfile = tempnam($zipdir, '');
        $zip = new ZipArchive();
        if ($zip->open($zipfile, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$zipfile>\n");
        }
        $groupfiles = Storage::allFiles($root);
        foreach ($groupfiles as $file) {
            if (Storage::exists($file)) {
                $zip->addFile(Storage::disk()->path($file), $file);
            }
        }
        $zip->close();
        return basename($zipfile);
    }
}
