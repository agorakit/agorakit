<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Setting extends Model {

	use RevisionableTrait;


	protected $fillable = ['name', 'value'];
	protected $rules = [
		'name' => 'required',
		'value' => 'required',
	];

	public $timestamps = true;
	protected $dates = ['deleted_at'];


	/**
	 * Static method to get a value from the settings table
	 */
	public static function get($key, $default = false)
	{
		$setting = \App\Setting::where('name', $key)->first();

	  if ($setting)
	  {
	    return $setting->value;
	  }

	  return $default;
	}


	/**
	 * Static method to set a value to the settings table
	 */
	public static function set($key, $value)
	{
			$setting = \App\Setting::firstOrNew(['name' => $key]);
			$setting->value = $value;
			$setting->save();
	}


}
