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

    if (\Auth::guest()) {
        return 0;
    }

        if (is_null($this->unreadcounter)) {

            if ($this->relationLoaded('userReadDiscussion'))
            {
              $counter = $this->userReaddiscussion->first();
            }
            else
            {
              dd('prout');
              $counter = $this->userReadDiscussion()->where('user_id', '=', Auth::user()->id)->first();
            }




            /*$counter = \App\UserReadDiscussion::where('user_id', '=', Auth::user()->id)
      ->where('discussion_id', '=', $this->id)->first();
      */

            if ($counter) {
                $this->unreadcounter = $this->total_comments - $counter->read_comments;
            } else {
                $this->unreadcounter = $this->total_comments;
            }
        }

        return $this->unreadcounter;
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
      return $this->hasMany('App\UserReadDiscussion');
    }

}
