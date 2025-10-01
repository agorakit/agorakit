<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

class Membership extends Model
{
    use RevisionableTrait;
    use ValidatingTrait;

    protected $table = 'membership';
    public $timestamps = true;
    protected $fillable = ['group_id', 'user_id', 'membership'];

    protected $casts = [
        'notifed_at' => 'datetime'
    ];

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
    public const ADMIN = 100; // group admin
    public const MEMBER = 20; // active member
    public const CANDIDATE = 10; // member asked to be part of the group, but it has not been confirmed yet
    public const INVITED = 0;  // member invited by a group admin
    public const UNREGISTERED = -10; // user left the group
    public const DECLINED = -15;  // user did not accept an invitation
    public const REMOVED = -20; // removed by admin for another reason
    public const BLACKLISTED = -30; // member is blacklisted and cannot join the group again (not yet in use)

    public function isAdmin()
    {
        return $this->membership == \App\Membership::ADMIN;
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Group::class);
    }
}
