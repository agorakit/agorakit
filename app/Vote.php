<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Vote extends Model {


	use SoftDeletes;
    use RevisionableTrait;

	protected $fillable = ['user_id', 'comment_id', 'vote'];
	protected $rules = [
		'user_id' => 'required|exists:users,id',
		'comment_id' => 'required|exists:comments,id',
	];


	protected $table = 'votes';
	public $timestamps = true;
	protected $dates = ['deleted_at'];


	public function comment()
	{
		return $this->belongsTo('App\comment');
	}

}
