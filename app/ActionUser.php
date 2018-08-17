<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;
use App\Traits\LogsActivity;

class ActionUser extends Model
{
    use ValidatingTrait;
    use RevisionableTrait;
    use SoftDeletes;
    use LogsActivity;

    protected $rules = [
        'user_id'  => 'required|exists:users,id',
        'action_id' => 'required|exists:actions,id',
    ];

    protected $fillable = [
        'user_id', 'action_id'
    ];


    protected $table = 'action_user';
    public $timestamps = true;

    public function action()
    {
        return $this->belongsTo(\App\Action::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
