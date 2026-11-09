<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Strip HTML tags from a stored text value when it is read from the database.
 *
 * This cast keeps plain-text values safe for display in contexts where HTML
 * formatting should not be preserved.
 */
class PlainText implements CastsAttributes
{
    /**
     * Cast the given value to plain text by removing any HTML markup.
     *
     * @param  array<string, mixed>  $attributes
     * @return mixed
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return strip_tags($value);
    }

    /**
     * Prepare the given value for storage without altering its content.
     *
     * @param  array<string, mixed>  $attributes
     * @return mixed
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value;
    }
}
