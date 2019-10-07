<?php

namespace App;

use Watson\Validating\ValidatingTrait;

class Tag extends \Cviebrock\EloquentTaggable\Models\Tag
{
    use ValidatingTrait;

    protected $rules = [
        'name'          => 'required',
        'normalized'    => 'unique:taggable_tags',
    ];



    /**
     * Generates a random color if none is set
     */
    public function getColorAttribute($value)
    {
        if ($value) {
            return $value;
        } else {
            return sprintf("#%06x",rand(0,16777215));
        }
    }



}
