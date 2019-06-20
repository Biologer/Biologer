<?php

namespace App\ActivityLog;

class FieldObservationDiff
{
    use MakesDiffs;

    /**
     * List of attributes for which we want to keep track of changes.
     *
     * @return array
     */
    protected static function attributesToDiff()
    {
        return [
            'taxon',
            'day',
            'month',
            'year',
            'location',
            'latitude',
            'longitude',
            'accuracy',
            'elevation',
            'photos',
            'sex',
            'stage',
            'number',
            'note',
            'project',
            'habitat',
            'found_on',
            'found_dead',
            'found_dead_note',
            'data_license',
            'time',
            'status',
            'types',
            'observer',
            'identifier',
            'dataset',
        ];
    }

    /**
     * If we want to display the value of changed attribute differently,
     * we define a function extract it here.
     *
     * @return array
     */
    protected static function valueOverrides()
    {
        return [
            'taxon' => function ($fieldObservation) {
                $taxon = optional($fieldObservation->observation->taxon);

                return [
                    'value' => $taxon->id,
                    'label' => $taxon->name ?? $fieldObservation->taxon_suggestion,
                ];
            },
            'stage' => function ($fieldObservation) {
                $stage = optional($fieldObservation->observation->stage);

                return [
                    'value' => $stage->id,
                    'label' => $stage->name ? "stages.{$stage->name}" : null,
                ];
            },
            'sex' => function ($fieldObservation) {
                $sex = $fieldObservation->observation->sex;

                return [
                    'value' => $sex,
                    'label' => $sex ? "labels.sexes.{$sex}" : null,
                ];
            },
            'found_dead' => function ($fieldObservation) {
                return [
                    'value' => $fieldObservation->found_dead,
                    'label' => $fieldObservation->found_dead ? 'Yes' : 'No',
                ];
            },
            'photos' => function ($fieldObservation) {
                return [
                    'value' => $fieldObservation->observation->photos->pluck('id')->sortBy('id')->all(),
                    'label' => null,
                ];
            },
            'types' => function ($fieldObservation) {
                return [
                    'value' => $fieldObservation->observation->types->sortBy('id')->all(),
                    'label' => null,
                ];
            },
            'data_license' => function ($fieldObservation) {
                $license = optional($fieldObservation->license());

                return [
                    'value' => $license->id,
                    'label' => $license->id ? 'licenses.'.$license->id : null,
                ];
            },
        ];
    }
}
