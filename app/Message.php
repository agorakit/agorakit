<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Watson\Validating\ValidatingTrait;

use App\User;
use Auth;

/**
 * A message is bvasically an email received in the application. 
 * It might at some point also be created by external contact forms for instance
 * 
 * It is short lived in the DB (a few days). It's purpose is to be converted to a discussion or a comment or... tbd
 */
class Message extends Model
{
    use RevisionableTrait;
    use ValidatingTrait;
    use SoftDeletes;

    protected $rules = [
        'subject'     => 'required',
        'raw'     => 'required',
    ];

    protected $keepRevisionOf = ['status'];
    
    protected $fillable = ['subject', 'to', 'from', 'body', 'raw', 'group_id', 'user_id', 'discussion_id', 'status'];


    public $timestamps = true;


    public function group()
    {
        return $this->belongsTo(\App\Group::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class)->withTrashed();
    }

    public function discussion()
    {
        return $this->belongsTo(\App\Discussion::class);
    }

}
