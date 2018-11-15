<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

class Comment extends Model
{
    use RevisionableTrait;
    use ValidatingTrait;
    use SoftDeletes;

    protected $rules = [
        'body'    => 'required|min:5',
        'user_id' => 'required|exists:users,id',
    ];

    protected $fillable = ['body'];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $with = ['user']; // always load users with comments

    protected $keepRevisionOf = ['body'];

    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function discussion()
    {
        return $this->belongsTo(\App\Discussion::class);
    }

    public function link()
    {
        return route('groups.discussions.show', [$this->discussion->group, $this->discussion]).'#comment_'.$this->id;
    }
}
