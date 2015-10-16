<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;

class Comment extends Model {

	use ValidatingTrait;

	protected $rules = [
		 'body' => 'required',
		 'user_id' => 'required'
 ];

	protected $fillable = ['body'];


	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $with = ['user'];

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
        return $this->belongsTo('App\Discussion');
    }

}
