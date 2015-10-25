<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;
use Auth;

//use Comment;

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

  // that was tricky to figure out : http://stackoverflow.com/questions/26727088/laravel-eager-loading-polymorphic-relations-related-models
  // we eager load the user with every discussion
  // TODO is it really a good idea?
  // protected $with = ['user', 'comments'];

  public $timestamps = true;

  public $unreadcounter;

  public $read_comments;

  protected $dates = ['deleted_at'];




  public function unReadCount()
  {

    if (\Auth::guest()) {
      return 0;
    }

    if ($this->userReadDiscussion->count() > 0)
    {
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
    if (\Auth::check())
    {
      return $this->hasMany('App\UserReadDiscussion')->where('user_id', "=", \Auth::user()->id);
    }
    else
    {
        abort(500, 'need to be logged in to access this userReadDiscussion relation');

    }
  }

}
