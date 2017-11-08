<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Config;

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

    /**
     * Static method to get a value from the settings table.
     */
    public static function get($key, $default = null)
    {
        $setting = \App\Setting::where('name', $key)->first();

        // first priority : setting stored in the DB
        if ($setting) {
            return $setting->value;
        }

        // second priority, default setting stored in app/config/agorakit.php
        if (Config::get('agorakit.' . $key))
        {
            return Config::get('agorakit.' . $key);
        }

        // lastly our $default
        return $default;
    }

    /**
     * Static method to set a value to the settings table.
     */
    public static function set($key, $value)
    {
        $setting = \App\Setting::firstOrNew(['name' => $key]);
        $setting->value = $value;
        $setting->save();
    }
}
