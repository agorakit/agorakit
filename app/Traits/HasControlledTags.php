<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

use App\Setting;
use App\Tag;
use Exception;

/** 
 * This trait allows any model to have "controlled" tags
 *
 * It means :
 * 
 * - a super admin can decide which tags are allowed on groups and users
 * - a group admin can decide which tags are allowed on discussions, files and actions
 * 
 */
trait HasControlledTags
{


    /** 
     * Returns the allowed tags for a model
     * 
     * Depends on the global settings for groups and users
     * Depends on the group settings for others
     * 
     * @return array
     */
    public function getAllowedTags()
    {
        if ($this instanceof User) {
            if (Setting::getArray('user_tags')) {
                return Setting::getArray('user_tags');
            } else {
                return [];
            }
        }

        if ($this instanceof Group) {
            if (Setting::getArray('group_tags')) {
                return Setting::getArray('group_tags');
            } else {
                return [];
            }
        }

        return [];
        
    }


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
     * Returns the current selected tags for a model
     * 
     * Enforce admin policies regarding tags : 
     * Will not return tags that are not allowed per admin settings even if they are attached to the model
     * 
     * @return array
     */
    public function getSelectedTags()
    {
       $selectedTags = [];

        if ($this instanceof User || $this instanceof Group) { // TODO find a way to make this work in traits...
            foreach ($this->tags as $tag) {
                if (in_array ($tag->name, \App\Services\TagService::getAllowedTagsFor($this)) || \App\Services\TagService::areNewTagsAllowedFor($this)) {
                    $selectedTags[] = $tag->name;
                }
            }
            
            Tag::whereIn('normalized', $selectedTags);
            return Tag::whereIn('normalized', $selectedTags);
        }
       
        
        return [];
    }



}


