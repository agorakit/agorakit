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
       'name'    => 'required',
       'body' => 'required',
       'user_id' => 'required'
   ];

    protected $table = 'discussions';
    protected $fillable = ['name', 'body', 'group_id'];



    // that was tricky to figure out : http://stackoverflow.com/questions/26727088/laravel-eager-loading-polymorphic-relations-related-models
    // we eager load the user with every discussion
    // TODO is it really a good idea?
    protected $with = ['user', 'comments'];

    public $timestamps = true;

    public $unreadcounter;



    protected $dates = ['deleted_at'];


    public function unReadCount()
    {
      // TODO : this should not be here, it's only for testing purposes.
      // The discussion list should load a count of unread replies instead, in a single query.
      // because else we do a query for eavery discussion to know the count. It smells
      // and we cannot order by unread discussions first...



      if (\Auth::guest())
      {
        return 0;
      }
      if (is_null($this->unreadcounter))
      {

        $this->unreadcounter = $this->comments()->leftJoin('user_read_discussion', function($join)
        {
          $join->on('user_read_discussion.discussion_id', '=', 'comments.discussion_id')
          ->where('user_read_discussion.user_id', '=', \Auth::user()->id)
          ->on('user_read_discussion.read_at', '>=', 'comments.created_at');
        })
        ->whereNull('user_read_discussion.id')
        ->where('comments.discussion_id', '=', $this->id)
        ->count();
      }

      return $this->unreadcounter;




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
}
