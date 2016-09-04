<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use App\User;
use App\Discussion;
use Watson\Validating\ValidatingTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Auth;


class Group extends Model
{
    use ValidatingTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use CascadeSoftDeletes;

    protected $cascadeDeletes = ['discussions', 'actions', 'files', 'memberships'];

    protected $rules = [
        'name' => 'required',
        'body' => 'required'
    ];

    protected $fillable = ['id', 'name', 'body', 'cover'];
    protected $casts = [ 'user_id' => 'integer' ];

    /**** various group types ****/
    // open group, default
    const OPEN = 0;
    const CLOSED = 1;
    // const SECRET = 2; // not in use for now



    /**
    * Returns the css color (yes) of this group. Curently random generated
    */
    public function color()
    {

        if ($this->color)
        {
            return $this->color;
        }
        else
        {
            $this->color = 'rgb(' . rand(0, 200) . ' , ' . rand(0, 200) . ' , ' . rand(0, 200) . ')';
            $this->save();
            return $this->color;
        }
    }


    /**
    * Returns all the users of this group
    *
    */
    public function users()
    {
        return $this->belongsToMany('App\User', 'membership')->withTimestamps();
    }

    /**
    * The user who crreated or updated this group title and description
    */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
    * return membership for the current user
    */
    public function membership()
    {
        return $this->belongsToMany('App\User', 'membership')
        ->where('user_id', "=", \Auth::user()->id)
        ->withPivot('membership');
    }


    public function memberships()
    {
        return $this->hasMany('App\Membership');
    }


    /**
    * Returns all the discussions belonging to this group
    *
    */
    public function discussions()
    {
        return $this->hasMany('App\Discussion');
    }


    /**
    * Returns all the actions belonging to this group
    *
    */
    public function actions()
    {
        return $this->hasMany('App\Action');
    }


    public function files()
    {
        return $this->hasMany('App\File');
    }

    /**
    *	Returns true if current user is a member of this group
    */
    public function isMember()
    {
        if (\Auth::check())
        {
            $member = $this->membership->first();
            if ($member && $member->pivot->membership == \App\Membership::MEMBER)
            {
                return true;
            }
        }

        return false;
    }


    /**
    * Returns membership info for curently logged user
    * Returns false if no membership found
    */
    public function getMembership()
    {
        if (\Auth::check())
        {
            $member = $this->membership->first();


            if ($member )
            {

                return $member->pivot->membership;
            }
        }
        return false;
    }


    /** constructs a links to the group **/
    public function link()
    {
        return action('GroupController@show', $this);
    }


    /** returns true if the group is public (viewable by all) **/
    public function isPublic()
    {
        if ($this->group_type == $this::OPEN)
        {
            return true;
        }
        else
        {
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
    * Scope a query to only include closed groups.
    *
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeClosed($query)
    {
        return $query->where('group_type', $this::CLOSED);
    }


}
