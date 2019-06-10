<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class Tag extends \Cviebrock\EloquentTaggable\Models\Tag
{

  use ValidatingTrait;


  protected $rules = [
    'name'     => 'required',
    'normalized'    => 'unique:taggable_tags',
  ];
  /*
  public function getRouteKeyName()
  {
  return 'normalized';
}
*/
}
