<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model {

	protected $table = 'membership';
	public $timestamps = true;

	protected $fillable = ['group_id', 'user_id'];


	public function votes()
	{
		return $this->morphedByMany('Vote', 'votable');
	}




}
