<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

class Comment extends Model
{
    use RevisionableTrait;
    use ValidatingTrait;
    use SoftDeletes;
    use SearchableTrait;

    protected $rules = [
        'body'    => 'required',
        'user_id' => 'required|exists:users,id',
    ];

    


    protected $fillable = ['body'];
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $with = ['user']; // always load users with comments

    protected $keepRevisionOf = ['body'];

    /*
    * Searchable rules.
    *
    * @var array
    */
   protected $searchable = [
       /*
       * Columns and their priority in search results.
       * Columns with higher values are more important.
       * Columns with equal values have equal importance.
       *
       * @var array
       */
       'columns' => [
           'comments.body'    => 10,
       ],
   ];

    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    public function votes()
    {
        return $this->hasMany('App\Vote');
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
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
