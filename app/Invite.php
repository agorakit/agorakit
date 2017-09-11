<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

class Invite extends Model
{
    use ValidatingTrait;
    use RevisionableTrait;

    public $timestamps = true;
    protected $dates = ['deleted_at'];

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
        $this->belongsTo('\App\User');
    }

    public function group()
    {
        $this->belongsTo('\App\Group');
    }
}
