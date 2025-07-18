<?php

namespace Agorakit\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class LocationFromJson implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        // Initialize $value to ""
        if (!$value) { $value = ""; }
        // Decoding the JSON field into an object
        $decoded = json_decode($value, false);
        if (!is_object($decoded)) {
          // This is probably an old `string` field, so we convert
          // putting all the string into `street` attribute
          $decoded = new \stdClass();
          $decoded->street = strval($value);
        }
        foreach($model->location_keys as $k) {
          if (!property_exists($decoded, $k)) {
            $decoded->$k = "";
          }
        }
        return $decoded;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return json_encode($value);
    }
}
