<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Config;
use Cache;

class Setting extends Model
{
    use RevisionableTrait;

    protected $fillable = ['name', 'value'];
    protected $rules = [
        'name'  => 'required',
        'value' => 'required',
    ];

    public $timestamps = true;
    protected $dates = ['deleted_at'];

    protected $keepRevisionOf = ['name', 'locale', 'value'];

    /**
    * Static method to get a value from the settings table.
    */
    public static function get($key, $default = null)
    {
        $setting = Cache::rememberForever('settings_' . $key, function () use ($key) {
            return \App\Setting::where('name', $key)->first();
        });


        // first priority : non empty setting stored in the DB
        if (isset($setting) && $setting->value) {
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
    public static function set($key, $value)
    {
        Cache::forget('settings_' . $key);
        $setting = \App\Setting::firstOrNew(['name' => $key]);
        $setting->value = $value;
        $setting->save();
    }
}
