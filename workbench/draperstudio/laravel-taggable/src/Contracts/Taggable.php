<?php

/*
 * This file is part of Laravel Taggable.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Taggable\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface Taggable.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
interface Taggable
{
    /**
     * @return mixed
     */
    public function tags();

    /**
     * @param $tags
     *
     * @return mixed
     */
    public function tag($tags);

    /**
     * @param $tags
     *
     * @return mixed
     */
    public function untag($tags);

    /**
     * @param $tags
     *
     * @return mixed
     */
    public function retag($tags);

    /**
     * @return mixed
     */
    public function detag();

    /**
     * @param Builder $query
     * @param $tags
     *
     * @return mixed
     */
    public function scopeWithAllTags(Builder $query, $tags);

    /**
     * @param Builder $query
     * @param array   $tags
     *
     * @return mixed
     */
    public function scopeWithAnyTags(Builder $query, $tags = array());

    /**
     * @return mixed
     */
    public static function tagsArray();

    /**
     * @return mixed
     */
    public static function tagsList();
}
