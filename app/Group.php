<?php

namespace App;

use App\Traits\HasStatus;
use App\Traits\HasControlledTags;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Storage;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

class Group extends Model
{
    use ValidatingTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Taggable;
    use Sluggable;
    use SearchableTrait;
    use HasStatus;
    use HasControlledTags;

    protected $rules = [
        'name' => 'required',
        'body' => 'required',
    ];

    protected $fillable = ['id', 'name', 'body', 'cover'];
    protected $casts = ['user_id' => 'integer', 'settings' => 'array'];

    protected $keepRevisionOf = ['name', 'body', 'cover', 'color', 'group_type', 'address', 'settings', 'status'];

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
            'groups.address' => 2,
        ],
    ];


    /**
    * Return the sluggable configuration array for this model.
    *
    * @return array
    */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
                'reserved' => ['reply', 'reply-', 'admin-'],
                'unique' => true,
            ],
        ];
    }

    /**
    * Returns the css color (yes) of this group. Curently random generated.
    */
    public function color()
    {
        if ($this->color) {
            return $this->color;
        } else {
            $this->color = 'rgb('.rand(0, 200).' , '.rand(0, 200).' , '.rand(0, 200).')';
            $this->save();

            return $this->color;
        }
    }

    /**
    * Returns all the users of this group.
    */
    public function users()
    {
        return $this->belongsToMany(\App\User::class, 'membership')->where('membership', '>=', \App\Membership::MEMBER)->withTimestamps()->withPivot('membership');
    }

    /**
    * Returns all the admins of this group.
    */
    public function admins()
    {
        return $this->belongsToMany(\App\User::class, 'membership')->where('membership', \App\Membership::ADMIN)->withTimestamps()->withPivot('membership');
    }

    /**
    * Returns all the candidates of this group.
    */
    public function candidates()
    {
        return $this->hasMany(\App\Membership::class)->where('membership', \App\Membership::CANDIDATE);
    }

    /**
    * The user who created or updated this group title and description.
    */
    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    /**
    * return membership for the current user.
    */
    public function membership()
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

    public function memberships()
    {
        return $this->hasMany(\App\Membership::class);
    }

    /**
    * Returns all the discussions belonging to this group.
    */
    public function discussions()
    {
        return $this->hasMany(\App\Discussion::class);
    }

    /**
    * Returns all the actions belonging to this group.
    */
    public function actions()
    {
        return $this->hasMany(\App\Action::class);
    }

    public function files()
    {
        return $this->hasMany(\App\File::class);
    }

    public function invites()
    {
        return $this->hasMany(\App\Invite::class);
    }

    public function activities()
    {
        return $this->hasMany(\App\Activity::class)->orderBy('created_at', 'desc');
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
            return config('agorakit.inbox_prefix').$this->slug.config('agorakit.inbox_suffix');
        }
        else {
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

    /**
    * Return true if the group has a cover image.
    */
    public function hasCover()
    {
        $path = '/groups/'.$this->id.'/cover.jpg';

        return Storage::disk('local')->exists($path);
    }

    /**
    * Geocode the item
    * Returns true if it worked, false if it didn't.
    */
    public function geocode()
    {
        if ($this->address == '') {
            $this->latitude = 0;
            $this->longitude = 0;

            return true;
        }

        $geocode = geocode($this->address);

        if ($geocode) {
            $this->latitude = $geocode['latitude'];
            $this->longitude = $geocode['longitude'];

            return true;
        }

        return false;
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

}
