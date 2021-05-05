<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Membership extends Model
{
    use RevisionableTrait;

    protected $table = 'membership';
    public $timestamps = true;
    protected $fillable = ['group_id', 'user_id', 'membership'];
    protected $dates = ['notifed_at'];
    protected $rules = [
        'user_id'  => 'required|exists:users,id',
        'group_id' => 'required|exists:groups,id',
    ];

    // Default notification interval is hourly :
    protected $attributes = [
        'notification_interval' => 60,
    ];

    protected $keepRevisionOf = ['config', 'membership', 'notification_interval'];

    // Membership levels
    const ADMIN = 100; // group admin
    const MEMBER = 20; // active member
    const CANDIDATE = 10; // member asked to be part of the group, but it has not been confirmed yet
    const INVITED = 0;  // member invited by a group admin    
    const UNREGISTERED = -10; // user left the group
    const DECLINED = -15;  // user did not accept an invitation
    const REMOVED = -20; // removed by admin for another reason
    const BLACKLISTED = -30; // member is blacklisted and cannot join the group again (not yet in use)

    public function isAdmin()
    {
        return $this->membership == \App\Membership::ADMIN;
    }

    public function votes()
    {
        return $this->morphedByMany('Vote', 'votable');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    public function group()
    {
        return $this->belongsTo(\App\Group::class);
    }
}
