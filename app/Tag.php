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
    * Generates a random color if none is set, and saves the tag.
    */
    public function getColorAttribute($value)
    {
        if ($value) {
            return $value;
        } else {
            $color = sprintf("#%06x", rand(0, 16777215));
            $this->color = $color;
            $this->save();
            return $color;
        }
    }
}
