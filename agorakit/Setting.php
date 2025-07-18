<?php

namespace Agorakit;

use Cache;
use Config;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;


/**
 * This model is used to store settings. Settings are stored in the settings table. This model support fluent use.
 *
 * setting()->localized($locale)->get($name) will return the $name setting using the $locale
 * setting()->localized()->get($name) will return the $name setting using current app locale
 * setting()->get($name) will return the $name setting value without locale support
 * setting($name) will return the $name setting value directly
 */
class Setting extends Model
{
    use RevisionableTrait;

    protected $fillable = ['name', 'value', 'locale'];
    protected $rules = [
        'name'  => 'required',
    ];

    public $timestamps = true;


    protected $casts = [
        'deleted_at' => 'datetime'
    ];

    protected $keepRevisionOf = ['name', 'locale', 'value'];

    protected $lang = null;

    /**
     * Method to get a value from the settings table.
     * If $this->localized() is used, the localized version is returned
     */
    public function get($key, $default = null)
    {
        if ($this->lang) {
            $locale = $this->lang;
            $setting = Cache::rememberForever('settings_' . $key . $locale, function () use ($key, $locale) {
                return \Agorakit\Setting::where('name', $key)->where('locale', $locale)->first();
            });
        } else {
            $setting = Cache::rememberForever('settings_' . $key, function () use ($key) {
                return \Agorakit\Setting::where('name', $key)->first();
            });
        }

        // first priority : non empty setting stored in the DB
        if ($setting && $setting->exists) {
            return $setting->value;
        }

        // second priority, default setting stored in app/config/agorakit.php
        if (config('agorakit.' . $key)) {
            return config('agorakit.' . $key);
        }

        // lastly our $default
        return $default;
    }

    public function localized($locale = false)
    {
        if ($locale) {
            $this->lang = $locale;
        } else {
            $this->lang = \App::getLocale();
        }

        return $this;
    }


    /**
     * Static method to set a value to the settings table.
     */
    public function set($key, $value)
    {
        if ($this->lang) {
            Cache::forget('settings_' . $key . $this->lang);
            $setting = \Agorakit\Setting::firstOrNew(['name' => $key, 'locale' => $this->lang]);
        } else {
            Cache::forget('settings_' . $key);
            $setting = \Agorakit\Setting::firstOrNew(['name' => $key]);
        }
        $setting->value = $value;
        $setting->save();

        return $setting;
    }


    /**
     * Static method to get an array from the settings table.
     */
    public function getArray($key, $default = null)
    {
        $setting  = $this->get($key);

        if (is_array($setting)) {
            return $setting;
        }

        // first priority : non empty setting stored in the DB
        if ($setting) {
            return json_decode($setting);
        }

        // second priority, default setting stored in app/config/agorakit.php
        if (config('agorakit.' . $key)) {
            return config('agorakit.' . $key);
        }

        // lastly our $default
        return $default;
    }

    /**
     * Static method to set a value to the settings table.
     */
    public function setArray($key, $value)
    {
        return $this->set($key, json_encode($value));
    }
}
