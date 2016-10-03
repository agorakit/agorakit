<?php

/*
 * This file is part of Laravel Taggable.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Taggable\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Stringy\StaticStringy as S;

/**
 * Class Tag.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
class Tag extends Eloquent
{
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function taggable()
    {
        return $this->morphTo();
    }

    /**
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $value = trim($value);
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = S::slugify($value);
    }

    /**
     * @param $name
     *
     * @return static
     */
    public static function findOrCreate($name)
    {
        if (!$tag = static::findByName($name)) {
            $tag = static::create(compact('name'));
        }

        return $tag;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public static function findByName($name)
    {
        return static::where('slug', S::slugify($name))->first();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getAttribute('name');
    }
}
