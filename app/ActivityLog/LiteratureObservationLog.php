<?php

namespace App\ActivityLog;

use App\LiteratureObservation;

class LiteratureObservationLog
{
    /**
     * Get changed literature observation data.
     *
     * @param  \App\LiteratureObservation  $updatedLiteratureObservation
     * @param  \App\LiteratureObservation  $oldLiteratureObservation
     * @return array
     */
    public static function changes(LiteratureObservation $updatedLiteratureObservation, LiteratureObservation $oldLiteratureObservation)
    {
        $updated = $updatedLiteratureObservation->toFlatArray();
        $oldData = $oldLiteratureObservation->toFlatArray();

        $data = [];
        $keyOverrides = self::keyOverrides();
        $valueOverrides = self::valueOverrides();

        foreach (self::attributesToDiff() as $attribute) {
            $value = $oldData[$attribute];

            if ($value === $updated[$attribute]) {
                continue;
            }

            if (isset($valueOverrides[$attribute])) {
                $extractValue = $valueOverrides[$attribute];

                $value = $extractValue($oldLiteratureObservation);
            }

            $key = $keyOverrides[$attribute] ?? $attribute;

            $data[$key] = $value;
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
        return [
            'taxon_id',
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
            'stage_id',
            'time',
            'dataset',
            'publication_id',
            'is_original_data',
            'cited_publication_id',
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
     * If we want to use a different key for attribute to display a field label
     * properly, we can define it here.
     *
     * @return array
     */
    protected static function keyOverrides()
    {
        return [
            'taxon_id' => 'taxon',
            'stage_id' => 'stage',
            'publication_id' => 'publication',
            'cited_publication_id' => 'cited_publication',
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
            'taxon_id' => function ($literatureObservation) {
                $taxon = optional($literatureObservation->observation->taxon);

                return [
                    'value' => $taxon->id,
                    'label' => $taxon->name,
                ];
            },
            'stage_id' => function ($literatureObservation) {
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
            'publication_id' => function ($literatureObservation) {
                $publication = optional($literatureObservation->publication);

                return [
                    'value' => $publication->id,
                    'label' => $publication->citation,
                ];
            },
            'cited_publication_id' => function ($literatureObservation) {
                $publication = optional($literatureObservation->citedPublication);

                return [
                    'value' => $publication->id,
                    'label' => $publication->citation,
                ];
            },
            'original_identification_validity' => function ($literatureObservation) {
                return [
                    'value' => $literatureObservation->original_identification_validity,
                    'label' => "labels.literature_observations.validity.{$literatureObservation->original_identification_validity}",
                ];
            },
        ];
    }
}
