<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;
use Cviebrock\EloquentTaggable\Taggable;

class User extends Authenticatable
{
    use Notifiable;
    use ValidatingTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use Sluggable;
    use Taggable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 'email', 'password', 'provider', 'provider_id',
    ];

    /**
    * The attributes excluded from the model's JSON form.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'remember_token', 'token',
    ];

    protected $casts = [
        'preferences' => 'array',
    ];

    protected $rules = [
        'name'     => 'required',
        'email'    => 'required|email|unique:users',
        'username' => 'alpha_dash|unique:users',
        //'password' => 'required',
    ];

    protected $keepRevisionOf = ['name', 'body', 'email', 'admin', 'preferences', 'address'];

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'users';

    /**
    * Get the route key for the model.
    *
    * @return string
    */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
    * Return the sluggable configuration array for this model.
    *
    * @return array
    */
    public function sluggable()
    {
        return [
            'username' => [
                'source'   => 'name',
                'reserved' => ['my'],
            ],
        ];
    }

    /**
    * Boot the model.
    *
    * @return void
    */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->token = str_random(30);

            // first created user is automatically an admin
            if (\App\User::count() == 0) {
                $user->admin = 1;
            }
        });
    }

    /**
    * Confirm the user.
    *
    * @return void
    */
    public function confirmEmail()
    {
        $this->verified = true;
        $this->token = null;
        $this->save();
    }

    /**
    * Returns true if the user is member of $group.
    */
    public function isMemberOf(Group $group)
    {
        // TODO refactor to avoid n+1
        // Always load membership with user
        $membership = \App\Membership::where('user_id', '=', $this->id)->where('group_id', '=', $group->id)->first();

        if ($membership && $membership->membership >= \App\Membership::MEMBER) {
            return true;
        }

        return false;
    }

    /**
    * Returns true if the user is admin of $group
    * TODO : candidate for refactoring, generates a lot of n+1 slowness : Membership could be serialized in a field of the user DB and be readily available all the time.
    */
    public function isAdminOf(Group $group)
    {
        foreach ($this->memberships as $membership) {
            //dd ($membership);
            if (($membership->group_id == $group->id) && ($membership->membership == \App\Membership::ADMIN)) {
                return true;
            }
        }

        return false;

        //$membership = \App\Membership::where('user_id', '=', $this->id)->where('group_id', '=', $group->id)->first();
        // the following might save us n+1 query problem later :
        $membership = $this->memberships()->where('group_id', '=', $group->id)->first();
        if ($membership && $membership->membership == \App\Membership::ADMIN) {
            return true;
        }

        return false;
    }


    /**
    * Returns true if the user is admin.
    */
    public function isAdmin()
    {
        if ($this->admin == 1) {
            return true;
        }

        return false;
    }

    /**
    * Returns true if the user's email is verified.
    */
    public function isVerified()
    {
        if ($this->verified == 1) {
            return true;
        }

        return false;
    }

    public function scopeAdmins($query)
    {
        return $query->where('admin', 1);
    }

    public function scopeVerified($query)
    {
        return $query->where('verified', 1);
    }

    /**
    * The groups this user is part of.
    */
    public function groups()
    {
        return $this->belongsToMany(\App\Group::class, 'membership')->where('membership.membership', '>=', \App\Membership::MEMBER)->orderBy('name')->withTimestamps();
    }

    /**
    * The actions this user attends to.
    */
    public function actions()
    {
        return $this->belongsToMany(\App\Action::class);
    }

    public function isAttending(Action $action)
    {
        return $action->users->contains($this->id);
    }

    public function memberships()
    {
        return $this->hasMany(\App\Membership::class);
    }

    public function discussionsSubscribed()
    {
        return $this->hasManyThrough(\App\Discussion::class, \App\Group::class);
    }

    /**
    * Discussions by this user.
    */
    public function discussions()
    {
        return $this->hasMany(\App\Discussion::class);
    }

    /**
    * Discussions by this user.
    */
    public function comments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    /**
    * Discussions by this user.
    */
    public function files()
    {
        return $this->hasMany(\App\File::class);
    }

    /**
    * Activities by this user.
    */
    public function activities()
    {
        return $this->hasMany(\App\Activity::class)->orderBy('created_at', 'desc');
    }

    public function link()
    {
        return route('users.show', [$this]);
    }

    // create and return an anonymous user
    // TODO refactor this to use a user service layer or something
    public static function getAnonymousUser()
    {
        $anonymous = \App\User::firstOrNew(['email' => 'anonymous@agorakit.org']);
        if ($anonymous->exists()) {
            return $anonymous;
        }
        $anonymous->name = 'Anonymous';
        $anonymous->body = 'Anonymous is a system user';
        $anonymous->verified = 1;

        $anonymous->save();

        return $anonymous;
    }

    /**
    * Geocode the user
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
    * Returns the current preference $key for the user, $default if not set.
    */
    public function getPreference($key, $default = false)
    {
        $preferences = $this->preferences;
        if (isset($preferences[$key])) {
            return $preferences[$key];
        } else {
            return $default;
        }
    }

    /**
    * Set the preference $key to $value for the user
    * No validation is made on this layer, preferences are stored in the json text field of the DB.
    */
    public function setPreference($key, $value)
    {
        $preferences = $this->preferences;
        $preferences[$key] = $value;
        $this->preferences = $preferences;

        return $this->save();
    }

}
