<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Watson\Validating\ValidatingTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Geocoder\Laravel\Facades\Geocoder;

class User extends Authenticatable
{

    use ValidatingTrait;
    use RevisionableTrait;

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
        'password', 'remember_token',
    ];

    protected $casts = [
        'preferences' => 'array',
    ];


    protected $rules = [
        'name' => 'required',
        'email' => 'required|email|unique:users',
        //'password' => 'required',
    ];

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'users';




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
            if (\App\User::count() == 0)
            {
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
    * Returns true if the user is member of $group
    */
    public function isMemberOf(Group $group)
    {
        $membership = \App\Membership::where('user_id', '=', $this->id)->where('group_id', '=', $group->id)->first();

        if ($membership && $membership->membership >= \App\Membership::MEMBER)
        {
            return true;
        }
        return false;
    }

    /**
    * Returns true if the user is admin of $group
    * TODO : candidate for refactoring, generates a lot of n+1 slowness : Membership could be serialized in a field of the user DB and be readily available all the time
    */
    public function isAdminOf(Group $group)
    {

        foreach ($this->memberships as $membership)
        {
            //dd ($membership);
            if (($membership->group_id == $group->id) && ($membership->membership == \App\Membership::ADMIN))
            {
                return true;
            }
        }

        return false;

        //$membership = \App\Membership::where('user_id', '=', $this->id)->where('group_id', '=', $group->id)->first();
        // the following might save us n+1 query problem later :
        $membership = $this->memberships()->where('group_id', '=', $group->id)->first();
        if ($membership && $membership->membership == \App\Membership::ADMIN)
        {
            return true;
        }
        return false;
    }


    /**
    * Returns the current preference $key for the user, $default if not set
    */
    public function getPreference($key, $default = false)
    {
        $preferences = $this->preferences;
        if (isset($preferences[$key]))
        {
            return $preferences[$key];
        }
        else
        {
            return $default;
        }
    }

    /**
    * Set the preference $key to $value for the user
    * No validation is made on this layer, preferences are stored in the json text field of the DB
    */
    public function setPreference($key, $value)
    {
        $preferences = $this->preferences;
        $preferences[$key] = $value;
        $this->preferences = $preferences;
        return $this->save();
    }

    /**
    * Returns trus if the user is admin
    */
    public function isAdmin()
    {
        if ($this->admin == 1)
        {
            return true;
        }

        return false;
    }


    /**
    * The groups this user is part of.
    */
    public function groups()
    {
        return $this->belongsToMany('App\Group', 'membership')->where('membership.membership', '>=', \App\Membership::MEMBER)->orderBy('name')->withTimestamps();
    }

    public function memberships()
    {
        return $this->hasMany('App\Membership');
    }


    public function discussionsSubscribed()
    {
        return $this->hasManyThrough('App\Discussion', 'App\Group');
    }

    /**
    * Discussions by this user.
    */
    public function discussions()
    {
        return $this->hasMany('App\Discussion');
    }

    /**
    * Discussions by this user.
    */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }


    /**
    * Discussions by this user.
    */
    public function files()
    {
        return $this->hasMany('App\File');
    }



    public function avatar()
    {
        return url('/users/' . $this->id . '/avatar');
    }


    public function cover()
    {
        return url('/users/' . $this->id . '/cover');
    }


    public function link()
    {
        return action('UserController@show', [$this]);
    }


    /**
    * Geocode the user
    * Returns true if it worked, false if it didn't
    */
    public function geocode()
    {

        if ($this->address == '')
        {
            $this->latitude = 0;
            $this->longitude = 0;
            return true;
        }

        try
        {
            $geocode = Geocoder::geocode($this->address)->get()->first();
        }
        catch (\Exception $e)
        {
            //$this->geocode_message = get_class($e) . ' / ' . $e->getMessage();
            return false;
        }


        $this->latitude = $geocode->getLatitude();
        $this->longitude = $geocode->getLongitude();
        return true;

    }




}
