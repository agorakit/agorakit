<?php

namespace Agorakit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;

/**
 * Wether a user participates or not to a specific action (event)
 */
class Participation extends Model
{
    use ValidatingTrait;
    use SoftDeletes;

    const UNDECIDED = 0;
    const PARTICIPATE = 10;
    const WONT_PARTICIPATE = -10;

    protected $attributes = [
        'status' => self::PARTICIPATE,
        'notification' => 60,
    ];

    protected $rules = [
        'user_id'   => 'required|exists:users,id',
        'action_id' => 'required|exists:actions,id',
    ];

    protected $fillable = [
        'user_id', 'action_id', 'notification', 'status'
    ];

    protected $table = 'action_user';
    public $timestamps = true;

    public function action()
    {
        return $this->belongsTo(\Agorakit\Action::class);
    }

    public function user()
    {
        return $this->belongsTo(\Agorakit\User::class)->withTrashed();
    }
}
