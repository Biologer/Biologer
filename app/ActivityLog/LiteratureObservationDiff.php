<?php

namespace App\ActivityLog;

class LiteratureObservationDiff
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
            'year',
            'month',
            'day',
            'elevation',
            'minimum_elevation',
            'maximum_elevation',
            'latitude',
            'longitude',
            'location',
            'accuracy',
            'georeferenced_by',
            'georeferenced_date',
            'observer',
            'identifier',
            'note',
            'sex',
            'number',
            'project',
            'found_on',
            'habitat',
            'stage',
            'time',
            'dataset',
            'publication',
            'is_original_data',
            'cited_publication',
            'place_where_referenced_in_publication',
            'original_date',
            'original_locality',
            'original_elevation',
            'original_coordinates',
            'original_identification',
            'original_identification_validity',
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
            'taxon' => function ($literatureObservation) {
                $taxon = optional($literatureObservation->observation->taxon);

                return [
                    'value' => $taxon->id,
                    'label' => $taxon->name,
                ];
            },
            'stage' => function ($literatureObservation) {
                $stage = optional($literatureObservation->observation->stage);

                return [
                    'value' => $stage->id,
                    'label' => $stage->name ? "stages.{$stage->name}" : null,
                ];
            },
            'sex' => function ($literatureObservation) {
                $sex = $literatureObservation->observation->sex;

                return [
                    'value' => $sex,
                    'label' => $sex ? "labels.sexes.{$sex}" : null,
                ];
            },
            'is_original_data' => function ($literatureObservation) {
                return [
                    'value' => $literatureObservation->is_original_data,
                    'label' => $literatureObservation->is_original_data ? 'Yes' : 'No',
                ];
            },
            'publication' => function ($literatureObservation) {
                $publication = optional($literatureObservation->publication);

                return [
                    'value' => $publication->id,
                    'label' => $publication->citation,
                ];
            },
            'cited_publication' => function ($literatureObservation) {
                $publication = optional($literatureObservation->citedPublication);

                return [
                    'value' => $publication->id,
                    'label' => $publication->citation,
                ];
            },
            'original_identification_validity' => function ($literatureObservation) {
                $key = mb_strtolower($literatureObservation->identificationValidity()->getKey());

                return [
                    'value' => $literatureObservation->original_identification_validity,
                    'label' => "labels.literature_observations.validity.{$key}",
                ];
            },
        ];
    }
}
