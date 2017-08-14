<?php

namespace App\Concerns;

use App\DynamicFieldValue;
use App\DynamicFields\DynamicField;

trait HasDynamicFields
{
    /**
     * Name of the attribute under which values for dynamic fields are stored.
     *
     * @return string
     */
    public function dynamicFieldsAttribute()
    {
        return 'dynamic_fields';
    }

    /**
     * Available dynamic fields.
     *
     * @return array
     */
    public static function availableDynamicFields()
    {
        return [];
    }

    /**
     * [Names of available fields.
     *
     * @return array
     */
    public static function availableDynamicFieldsNames()
    {
        return array_map(function ($field) {
            return DynamicField::classToName($field);
        }, static::availableDynamicFields());
    }

    /**
     * Dynamic fields mapped for frontend consumption.
     *
     * @return \Illuminate\Support\Collection
     */
    public function mappedDynamicFields()
    {
        return collect($this->{$this->dynamicFieldsAttribute()})->mapWithKeys(function ($value, $field) {
            return [DynamicField::classToName($field) => $value];
        })->toBase();
    }

    /**
     * Available dynamic fields mapped for frontend consumption.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function mappedAvailableDynamicFields()
    {
        return collect(static::availableDynamicFields())->map(function ($field) {
            return (new $field())->toArray();
        });
    }

    /**
     * Add dynamic fields
     *
     * @param array $fields
     */
    public function saveDynamicFields($fields = [])
    {
        $this->update([
            $this->dynamicFieldsAttribute() => collect($fields)->mapWithKeys(function ($value, $field) {
                return [DynamicField::classFromName($field) => $value];
            }),
        ]);
    }
}
