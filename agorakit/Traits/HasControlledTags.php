<?php

namespace Agorakit\Traits;

use Illuminate\Database\Eloquent\Builder;

use Agorakit\Setting;
use Agorakit\User;
use Agorakit\Group;
use Agorakit\Discussion;
use Agorakit\File;
use Agorakit\Action;
use Agorakit\Tag;
use Exception;

/**
 * This trait allows any model to have "controlled" tags
 *
 * It means :
 *
 * - a super admin can decide which tags are allowed on groups and users
 * - a group admin can decide which tags are allowed on discussions, files and actions
 * - if no controlled tags are defined, any tag can be added (aka free tagging)
 *
 */
trait HasControlledTags
{


    /**
     * Returns true if new tags are allowed for a model
     *
     * Depends on the global settings for groups and users
     *
     * Depends on the specific group settings for others
     *
     * @return boolean
     */
    public function areNewTagsAllowed()
    {
        if ($this instanceof User) {
            if (setting()->getArray('user_tags')) {
                return false;
            } else {
                return true;
            }
        }
        if ($this instanceof Group) {
            if (setting()->getArray('group_tags')) {
                return false;
            } else {
                return true;
            }
        }

        if ($this instanceof Discussion || $this instanceof File || $this instanceof Action) {
            if ($this->getAllowedTags()->count() > 0) {
                return false;
            } else {
                return true;
            }
        }

        return true;
    }



    /**
     * Returns the allowed tags for a model
     *
     * Depends on the global settings for groups and users
     * Depends on the group settings for others
     *
     * @return collection of Agorakit\Tag
     */
    public function getAllowedTags()
    {
        if ($this instanceof User) {
            if (setting()->getArray('user_tags')) {
                return $this->arrayToTags(setting()->getArray('user_tags'));
            } else {
                return collect();
            }
        }

        if ($this instanceof Group) {
            if (setting()->getArray('group_tags')) {
                return $this->arrayToTags(setting()->getArray('group_tags'));
            } else {
                return collect();
            }
        }

        if ($this instanceof Discussion || $this instanceof File || $this instanceof Action) {
            return $this->arrayToTags($this->group->getSetting('allowed_tags'));
        }

        throw new Exception('unknown class type');
        return collect();
    }





    /**
     * Returns the current selected tags for a model
     *
     * Enforce admin policies regarding tags :
     * Will not return tags that are not allowed per admin settings even if they are attached to the model
     *
     * @return collection of Agorakit\Tag
     */
    public function getSelectedTags()
    {
        $selectedTags = collect();


        foreach ($this->tags as $tag) {
            if ($this->getAllowedTags()->contains($tag->name) || $this->areNewTagsAllowed()) {
                $selectedTags->push($tag);
            }
        }

        return $selectedTags->unique('normalized')->sortBy('normalized');
    }


    /**
     * Returns a collection of tags used by the content of the same type,
     * in the same group, or the allowed tags if the tags are limited in this context.
     */
    public function getTagsInUse()
    {
        if ($this instanceof Discussion || $this instanceof File || $this instanceof Action) {

            $models = collect();
            $tags = collect();

            if ($this->areNewTagsAllowed()) {
                if ($this instanceof Discussion) {
                    $models = $this->group
                        ->discussions()
                        ->with('tags')
                        ->get();
                }

                if ($this instanceof File) {
                    $models = $this->group
                        ->files()
                        ->with('tags')
                        ->get();
                }

                if ($this instanceof Action) {
                    $models = $this->group
                        ->actions()
                        ->with('tags')
                        ->get();
                }

                foreach ($models as $model) {
                    $tags = $tags->merge($model->tags);
                }
                return $tags->unique('normalized')->sortBy('normalized');
            } else {
                return $this->getAllowedTags();
            }
        }

        if ($this instanceof Group) {
            // Generate a list of tags from this group :
            // One day, groups might choose to have their own, fixed tag list, configured by admin
            // this day has come :-)

            $tags = collect();

            $discussions = $this->discussions()
                ->with('tags')
                ->get();

            $files = $this->files()
                ->with('tags')
                ->get();

            foreach ($discussions as $discussion) {
                foreach ($discussion->getSelectedTags() as $tag) {
                    $tags->push($tag);
                }
            }

            foreach ($files as $file) {
                foreach ($file->getSelectedTags() as $tag) {
                    $tags->push($tag);
                }
            }

            return $tags->unique('normalized')->sortBy('normalized');
        }

        return false;
    }


    /**
     * Utility class
     *
     * Convert an array of strings to a collection of tags
     * Tags are created in the DB if needed
     *
     * @return collection of Agorakit\Tag
     */
    private function arrayToTags($tagList)
    {
        $tags = collect();

        if (is_array($tagList)) {
            foreach ($tagList as $name) {
                if (!empty($name)) {
                    $tag = Tag::firstOrNew(['normalized' => trim(mb_strtolower($name))]);
                    $tag->name = $name;
                    $tag->save();
                    $tags->push($tag);
                }
            }
        }

        return $tags->unique('normalized')->sortBy('normalized');
    }
}
