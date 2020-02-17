<?php

namespace App\ActivityLog;

class CollectionObservationDiff
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
            'collection',
            'original_date',
            'original_locality',
            'original_elevation',
            'original_coordinates',
            'original_identification',
            'original_identification_validity',
            'verbatim_tag',
            'collecting_start_year',
            'collecting_start_month',
            'collecting_end_year',
            'collecting_end_month',
            'collecting_method',
            'collector',
            'catalogue_number',
            'cabinet_number',
            'box_number',
            'disposition',
            'type_status',
            'preparator',
            'preparation_method',
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
            'taxon' => function ($collectionObservation) {
                $taxon = optional($collectionObservation->observation->taxon);

                return [
                    'value' => $taxon->id,
                    'label' => $taxon->name,
                ];
            },
            'stage' => function ($collectionObservation) {
                $stage = optional($collectionObservation->observation->stage);

                return [
                    'value' => $stage->id,
                    'label' => $stage->name ? "stages.{$stage->name}" : null,
                ];
            },
            'sex' => function ($collectionObservation) {
                $sex = $collectionObservation->observation->sex;

                return [
                    'value' => $sex,
                    'label' => $sex ? "labels.sexes.{$sex}" : null,
                ];
            },
            'collection' => function ($collectionObservation) {
                $collection = optional($collectionObservation->collection);

                return [
                    'value' => $collection->id,
                    'label' => $collection->name,
                ];
            },
            'original_identification_validity' => function ($collectionObservation) {
                $key = mb_strtolower($collectionObservation->identificationValidity()->getKey());

                return [
                    'value' => $collectionObservation->original_identification_validity,
                    'label' => "labels.literature_observations.validity.{$key}",
                ];
            },
            'verbatim_tag' => function ($collectionObservation) {
                return [
                    'value' => $collectionObservation->verbatim_tag,
                    'label' => null,
                ];
            },
        ];
    }
}
