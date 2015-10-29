<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;

class Discussion extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;
    use ValidatingTrait;
    use SoftDeletes;

    protected $rules = [
    'name' => 'required',
    'body' => 'required',
    'user_id' => 'required',
  ];

    protected $table = 'discussions';
    protected $fillable = ['name', 'body', 'group_id'];

    public $timestamps = true;

    public $unreadcounter;

    public $read_comments;

    protected $dates = ['deleted_at'];

    public function unReadCount()
    {
        if (\Auth::guest()) {
            return 0;
        }

        if ($this->userReadDiscussion->count() > 0) {
            return $this->total_comments - $this->userReadDiscussion->first()->read_comments;
        }

        return $this->total_comments;
    }

  // adds a reply to this discussion
  public function reply(Comment $comment)
  {
      $this->comments()->save($comment);
      ++$this->total_comments;
      $this->save();
  }

    public function group()
    {
        return $this->belongsTo('App\Group');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function userReadDiscussion()
    {
        if (\Auth::check()) {
            return $this->hasMany('App\UserReadDiscussion')->where('user_id', '=', \Auth::user()->id);
        } else {
            abort(500, 'Need to be logged in to access this userReadDiscussion relation');
        }
    }
}
