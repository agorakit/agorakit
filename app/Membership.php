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

    // Membership levels

    // active member
    const ADMIN = 100;

    // active member
    const MEMBER = 20;

    // member asked to be part of the group, but it has not been confirmed yet
    const CANDIDATE = 10;

    // invited
    const INVITED = 0;

    // left the group
    const UNREGISTERED = -10;

    // removed by admin
    const REMOVED = -20;

    // member is blacklisted and cannot join the group again (not yet in use)
    const BLACKLISTED = -30;

    public function votes()
    {
        return $this->morphedByMany('Vote', 'votable');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function group()
    {
        return $this->belongsTo(\App\Group::class);
    }
}
