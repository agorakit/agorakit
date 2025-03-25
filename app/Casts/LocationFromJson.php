<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class LocationFromJson implements CastsAttributes
{
    private $location_keys = ["name", "street", "city", "county", "country"];

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
        if (!$decoded = json_decode($value)) {
          // This is probably an old `string` field, so we convert
          // putting all the string into `street` attribute
          $decoded = new \stdClass();
          $decoded->street = $value;
        }
        foreach($this->location_keys as $key) {
          if (!property_exists($decoded, $key)) {
            $decoded->key = "";
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
