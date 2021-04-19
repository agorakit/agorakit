<?php

namespace App;

use Cache;
use Config;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

class Setting extends Model
{
    use RevisionableTrait;

    protected $fillable = ['name', 'value'];
    protected $rules = [
        'name'  => 'required',
    ];

    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $keepRevisionOf = ['name', 'locale', 'value'];

    /**
     * Static method to get a value from the settings table.
     * It returns the localized version if found
     */
    public static function get($key, $default = null, $locale = null)
    {
        //$locale = \App::getLocale();

        $setting = Cache::rememberForever('settings_' . $key . $locale, function () use ($key, $locale) {
            if (\App\Setting::where('name', $key)->where('locale', $locale)->count() > 0) {
                return \App\Setting::where('name', $key)->where('locale', $locale)->first();
            }
            return \App\Setting::where('name', $key)->first();
        });



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



    /**
     * Static method to set a value to the settings table.
     */
    public static function set($key, $value, $locale = null)
    {
        Cache::forget('settings_' . $key . $locale);

        if ($locale) {
            $setting = \App\Setting::firstOrNew(['name' => $key, 'locale' => $locale]);
        } else {
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
