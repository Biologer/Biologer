<?php

namespace App\Concerns;

use App\DynamicFieldValue;
use App\DynamicFields\DynamicField;

trait HasDynamicFields
{
    /**
     * Available dynamic fields.
     *
     * @return array
     */
    public static function dynamicFields()
    {
        return [];
    }

    /**
     * Available dynamic fields mapped for frontend consumption.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function availableDynamicFields()
    {
        return collect(static::dynamicFields())->map(function ($field, $name) {
            return (new $field($name))->toArray();
        })->values();
    }
}
