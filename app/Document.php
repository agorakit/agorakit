<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Document extends Model {

	use RevisionableTrait;
	use SoftDeletes;

	protected $table = 'documents';
	public $timestamps = true;



	protected $dates = ['deleted_at'];

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function group()
	{
		return $this->belongsTo('User');
	}

}
