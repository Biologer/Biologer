<?php

namespace App\ActivityLog;

use App\Contracts\FlatArrayable;

trait MakesDiffs
{
    /**
     * Get changed data.
     *
     * The diff data for one item can be returned as:
     *
     * - value|null
     * - ['value' => 'value|null', 'label' => 'translatableString|null']
     *
     * @param  \App\Contracts\FlatArrayable  $updatedFieldObservation
     * @param  \App\Contracts\FlatArrayable  $oldFieldObservation
     * @return array
     */
    public static function changes(FlatArrayable $updatedFieldObservation, FlatArrayable $oldFieldObservation)
    {
        $updatedData = $updatedFieldObservation->toFlatArray();
        $oldData = $oldFieldObservation->toFlatArray();

        $data = [];
        $valueOverrides = static::valueOverrides();

        foreach (static::attributesToDiff() as $attribute) {
            $value = $oldData[$attribute] ?? null;
            $updated = $updatedData[$attribute] ?? null;

            if (isset($valueOverrides[$attribute])) {
                $extractValue = $valueOverrides[$attribute];

                $value = $extractValue($oldFieldObservation);
                $updated = $extractValue($updatedFieldObservation);
            }

            if ($value === $updated) {
                continue;
            }

            $data[$attribute] = $value;
        }

        return $data;
    }

    /**
     * List of attributes for which we want to keep track of changes.
     *
     * @return array
     */
    protected static function attributesToDiff()
    {
        return [];
    }

    /**
     * If we want to display the value of changed attribute differently,
     * we define a function extract it here.
     *
     * @return array
     */
    protected static function valueOverrides()
    {
        return [];
    }
}
