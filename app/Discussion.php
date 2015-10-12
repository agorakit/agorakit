<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

//use Comment;

class Discussion extends Model
{
    use \Venturecraft\Revisionable\RevisionableTrait;

    protected $table = 'discussions';
    protected $fillable = ['name', 'body', 'group_id', 'parent_id'];

    // that was tricky to figure out : http://stackoverflow.com/questions/26727088/laravel-eager-loading-polymorphic-relations-related-models
    // we eager load the user with every discussion
    // TODO is it really a good idea?
    protected $with = ['user', 'comments'];

    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

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
        return $this->morphMany('App\Vote', 'votable');
    }

    public function comments()
    {
        return $this->morphMany('App\Comment', 'commentable');
    }
}
