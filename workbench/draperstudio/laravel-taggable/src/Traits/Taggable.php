<?php

/*
 * This file is part of Laravel Taggable.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Taggable\Traits;

use DraperStudio\Taggable\Exceptions\InvalidTagException;
use DraperStudio\Taggable\Models\Tag;
use DraperStudio\Taggable\Util;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Stringy\StaticStringy as S;

/**
 * Class Taggable.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
trait Taggable
{
    /**
     * @return mixed
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    /**
     * @param $tags
     *
     * @return $this
     */
    public function tag($tags)
    {
        $tags = Util::buildTagArray($tags);

        foreach ($tags as $tag) {
            $this->addOneTag($tag);
        }

        return $this;
    }

    /**
     * @param $tags
     *
     * @return $this
     */
    public function untag($tags)
    {
        $tags = Util::buildTagArray($tags);

        foreach ($tags as $tag) {
            $this->removeOneTag($tag);
        }

        return $this;
    }

    /**
     * @param $tags
     *
     * @return $this
     */
    public function retag($tags)
    {
        return $this->detag()->tag($tags);
    }

    /**
     * @return $this
     */
    public function detag()
    {
        $this->removeAllTags();

        return $this;
    }

    /**
     * @param $string
     */
    protected function addOneTag($string)
    {
        if ($this->onlyUseExistingTags) {
            $tag = Tag::findByName($string);

            if (empty($tag)) {
                throw new InvalidTagException("$string was not found in the list of tags.");
            }
        } else {
            $tag = Tag::findOrCreate($string);
        }

        if (!$this->tags instanceof Collection) {
            $this->tags = new Collection($this->tags);
        }

        if (!$this->tags->contains($tag->getKey())) {
            $this->tags()->attach($tag);
        }
    }

    /**
     * @param $string
     */
    protected function removeOneTag($string)
    {
        if ($tag = Tag::findByName($string)) {
            $this->tags()->detach($tag);
        }
    }

    /**
     *
     */
    protected function removeAllTags()
    {
        $this->tags()->sync([]);
    }

    /**
     * @return string
     */
    public function getTagListAttribute()
    {
        return Util::makeTagList($this, 'name');
    }

    /**
     * @return string
     */
    public function getTagListNormalizedAttribute()
    {
        return Util::makeTagList($this, 'slug');
    }

    /**
     * @return mixed
     */
    public function getTagArrayAttribute()
    {
        return Util::makeTagArray($this, 'name');
    }

    /**
     * @return mixed
     */
    public function getTagArrayNormalizedAttribute()
    {
        return Util::makeTagArray($this, 'slug');
    }

    /**
     * @param Builder $query
     * @param $tags
     *
     * @return Builder|static
     */
    public function scopeWithAllTags(Builder $query, $tags)
    {
        $tags = Util::buildTagArray($tags);
        $slug = array_map([S::class, 'slugify'], $tags);

        return $query->whereHas('tags', function ($q) use ($slug) {
            $q->whereIn('slug', $slug);
        }, '=', count($slug));
    }

    /**
     * @param Builder $query
     * @param array   $tags
     *
     * @return Builder|static
     */
    public function scopeWithAnyTags(Builder $query, $tags = [])
    {
        $tags = Util::buildTagArray($tags);

        if (empty($tags)) {
            return $query->has('tags');
        }

        $slug = array_map([S::class, 'slugify'], $tags);

        return $query->whereHas('tags', function ($q) use ($slug) {
            $q->whereIn('slug', $slug);
        });
    }

    /**
     * @return mixed
     */
    public static function tagsArray()
    {
        return static::getAllTags(get_called_class());
    }

    /**
     * @return string
     */
    public static function tagsList()
    {
        return Util::joinArray(static::getAllTags(get_called_class()));
    }

    /**
     * @param $className
     *
     * @return mixed
     */
    public static function getAllTags($className)
    {
        return DB::table('taggables')->distinct()
            ->where('taggable_type', '=', $className)
            ->join('tags', 'taggables.tag_id', '=', 'tags.id')
            ->orderBy('tags.slug')
            ->lists('tags.slug');
    }
}
