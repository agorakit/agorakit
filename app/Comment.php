<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;
use App\Traits\LogsActivity;
use App\Traits\MentionUsers;

class Comment extends Model
{
    use RevisionableTrait;
    use ValidatingTrait;
    use SoftDeletes;
    use LogsActivity;
    use MentionUsers;

    protected $rules = [
        'body'    => 'required|min:5',
        'user_id' => 'required|exists:users,id',
    ];

    protected $fillable = ['body'];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $with = ['user']; // always load users with comments

    //protected $touches = ['discussion', 'user'];

    protected $casts = ['user_id' => 'integer'];
    protected $dontKeepRevisionOf = ['vote'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function discussion()
    {
        return $this->belongsTo('App\Discussion')->withTrashed();
    }

    public function link()
    {
        return route('groups.discussions.show', [$this->discussion->group, $this->discussion]).'#comment_'.$this->id;
    }
}
