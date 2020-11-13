<?php

namespace App\Services;

use App\Setting;
use App\Group;
use App\User;
use Exception;

/** 
 * Utilities related to tag management
 * 
 * TODO: This could be refactored to a trait that would replace cviebrock/eloquent-taggable
 * 
 */
class TagService
{
    /** 
     * Returns the allowed tags for a model
     * 
     * Depends on the global settings for groups and users
     * Depends on the group settings for others
     * 
     * @return array
     */
    static function getAllowedTagsFor($model)
    {
        if ($model instanceof User) {
            if (Setting::getArray('user_tags')) {
                return Setting::getArray('user_tags');
            } else {
                return [];
            }
        }

        if ($model instanceof Group) {
            if (Setting::getArray('group_tags')) {
                return Setting::getArray('group_tags');
            } else {
                return [];
            }
        }

        throw new Exception('$model is not of a supported type');
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
    static function areNewTagsAllowedFor($model)
    {
        if ($model instanceof User) {
            if (Setting::getArray('user_tags')) {
                return false;
            } else {
                return true;
            }
        }
        if ($model instanceof Group) {
            if (Setting::getArray('group_tags')) {
                return false;
            } else {
                return true;
            }
        }
        
        throw new Exception('$model is not of a supported type');
    }


    /** 
     * Returns the current selected tags for a model
     * 
     * Enforce admin policies regarding tags : 
     * Will not return tags that are not allowed per admin settings even if they are attached to the model
     * 
     * @return array
     */
    static function getSelectedTagsFor($model)
    {
       $selectedTags = [];

        if ($model instanceof User || $model instanceof Group) {
            foreach ($model->tags as $tag) {
                if (in_array ($tag->name, \App\Services\TagService::getAllowedTagsFor($model)) || \App\Services\TagService::areNewTagsAllowedFor($model)) {
                    $selectedTags[] = $tag->name;
                }
            }
            return $selectedTags;
        }
       
        
        throw new Exception('$model is not of a supported type');
    }



}
