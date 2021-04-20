<?php

namespace App;

use Cache;
use Config;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;


/**
 * This model is used to store settings. Settings are stored in the settings table. This model support fluent use.
 * 
 * setting()->localized($locale)->get($name) will return the $name setting using the $locale 
 * setting()->localized()->get($name) will return the $name setting using current app locale
 * setting()->get($name) will return the setting without locale support
 * 
 */
class Setting extends Model
{
    use RevisionableTrait;

    protected $fillable = ['name', 'value', 'locale'];
    protected $rules = [
        'name'  => 'required',
    ];

    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $keepRevisionOf = ['name', 'locale', 'value'];

    protected $lang = null;

    /**
     * Method to get a value from the settings table.
     * It returns the localized version if found
     */
    public function get($key, $default = null)
    {
        if ($this->lang) {
            $locale = $this->lang;
            $setting = Cache::rememberForever('settings_' . $key . $locale, function () use ($key, $locale) {
                return \App\Setting::where('name', $key)->where('locale', $locale)->first();
            });
        } else {
            $setting = Cache::rememberForever('settings_' . $key, function () use ($key) {
                return \App\Setting::where('name', $key)->first();
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
            $setting = \App\Setting::firstOrNew(['name' => $key, 'locale' => $this->lang]);
        } else {
            Cache::forget('settings_' . $key);
            $setting = \App\Setting::firstOrNew(['name' => $key]);
        }
        $setting->value = $value;
        $setting->save();

        return $setting;
    }


    /** 
     * Static method to get an array from the settings table.
     */
    public static function getArray($key, $default = null)
    {
        $setting = Cache::rememberForever('settings_' . $key, function () use ($key) {
            return \App\Setting::where('name', $key)->first();
        });

        // first priority : non empty setting stored in the DB
        if ($setting && $setting->exists) {
            return json_decode($setting->value);
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
    public static function setArray($key, $value)
    {
        Cache::forget('settings_' . $key);
        $setting = \App\Setting::firstOrNew(['name' => $key]);
        $setting->value = json_encode($value);
        $setting->save();

        return $setting;
    }
}
