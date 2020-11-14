<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

use App\Setting;
use App\User;
use App\Group;
use App\Discussion;
use App\File;
use App\Action;
use App\Tag;
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
            if (Setting::getArray('user_tags')) {
                return false;
            } else {
                return true;
            }
        }
        if ($this instanceof Group) {
            if (Setting::getArray('group_tags')) {
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
     * @return collection of App\Tag
     */
    public function getAllowedTags()
    {
        if ($this instanceof User) {
            if (Setting::getArray('user_tags')) {
                return $this->arrayToTags(Setting::getArray('user_tags'));
            } else {
                return collect();
            }
        }

        if ($this instanceof Group) {
            if (Setting::getArray('group_tags')) {
                return $this->arrayToTags(Setting::getArray('group_tags'));
            } else {
                return collect();
            }
        }

        if ($this instanceof Discussion || $this instanceof File || $this instanceof Action) {
            return $this->arrayToTags($this->group->getSetting('allowed_tags'));   
        }

        throw new Exception ('unknown class type');
        return collect();
    }





    /** 
     * Returns the current selected tags for a model
     * 
     * Enforce admin policies regarding tags : 
     * Will not return tags that are not allowed per admin settings even if they are attached to the model
     * 
     * @return collection of App\Tag
     */
    public function getSelectedTags()
    {
        $selectedTags = collect();


        foreach ($this->tags as $tag) {
            if ($this->getAllowedTags()->contains($tag->name) || $this->areNewTagsAllowed()) {
                $selectedTags->push($tag);
            }
        }

        return $selectedTags;
    }


    /** 
     * Utility class
     * 
     * Convert an array of strings to a collection of tags
     * Tags are created in the DB if needed
     * 
     * @return collection of App\Tag
     */
    private function arrayToTags($tagList)
    {
        $tags = collect();

        if (is_array($tagList)) {
        }
        foreach ($tagList as $name) {
            $tags->push(Tag::firstOrCreate(['name' => $name]));
        }

        return $tags;
    }
}
