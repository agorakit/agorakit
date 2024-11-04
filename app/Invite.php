<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Invite extends Model
{
    use ValidatingTrait;

    public $timestamps = true;

    protected $casts = [
        'deleted_at' => 'datetime'
    ];

    protected $rules = [
        'token'    => 'required',
        'email'    => 'required|email',
        'user_id'  => 'required|exists:users,id',
        'group_id' => 'required|exists:groups,id',
    ];

    public function generatetoken()
    {
        $this->token = str_random(30);
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
