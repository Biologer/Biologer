<?php

namespace App\Exports\LiteratureObservations;

use App\Models\Export;
use App\Exports\BaseExport;
use App\Models\LiteratureObservation;

class CustomLiteratureObservationsExport extends BaseExport
{
    /**
     * Column labels and names.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function columnData()
    {
        return collect([
            [
                'label' => trans('labels.id'),
                'value' => 'id',
            ],
            [
                'label' => trans('labels.literature_observations.taxon'),
                'value' => 'taxon',
            ],
            [
                'label' => trans('labels.literature_observations.year'),
                'value' => 'year',
            ],
            [
                'label' => trans('labels.literature_observations.month'),
                'value' => 'month',
            ],
            [
                'label' => trans('labels.literature_observations.day'),
                'value' => 'day',
            ],
            [
                'label' => trans('labels.literature_observations.elevation_m'),
                'value' => 'elevation',
            ],
            [
                'label' => trans('labels.literature_observations.minimum_elevation_m'),
                'value' => 'minimum_elevation',
            ],
            [
                'label' => trans('labels.literature_observations.maximum_elevation_m'),
                'value' => 'maximum_elevation',
            ],
            [
                'label' => trans('labels.literature_observations.latitude'),
                'value' => 'latitude',
            ],
            [
                'label' => trans('labels.literature_observations.longitude'),
                'value' => 'longitude',
            ],
            [
                'label' => trans('labels.literature_observations.mgrs10k'),
                'value' => 'mgrs10k',
            ],
            [
                'label' => trans('labels.literature_observations.location'),
                'value' => 'location',
            ],
            [
                'label' => trans('labels.literature_observations.accuracy_m'),
                'value' => 'accuracy',
            ],
            [
                'label' => trans('labels.literature_observations.georeferenced_by'),
                'value' => 'georeferenced_by',
            ],
            [
                'label' => trans('labels.literature_observations.georeferenced_date'),
                'value' => 'georeferenced_date',
            ],
            [
                'label' => trans('labels.literature_observations.observer'),
                'value' => 'observer',
            ],
            [
                'label' => trans('labels.literature_observations.identifier'),
                'value' => 'identifier',
            ],
            [
                'label' => trans('labels.literature_observations.note'),
                'value' => 'note',
            ],
            [
                'label' => trans('labels.literature_observations.sex'),
                'value' => 'sex',
            ],
            [
                'label' => trans('labels.literature_observations.number'),
                'value' => 'number',
            ],
            [
                'label' => trans('labels.literature_observations.project'),
                'value' => 'project',
            ],
            [
                'label' => trans('labels.literature_observations.found_on'),
                'value' => 'found_on',
            ],
            [
                'label' => trans('labels.literature_observations.habitat'),
                'value' => 'habitat',
            ],
            [
                'label' => trans('labels.literature_observations.stage'),
                'value' => 'stage',
            ],
            [
                'label' => trans('labels.literature_observations.time'),
                'value' => 'time',
            ],
            [
                'label' => trans('labels.literature_observations.dataset'),
                'value' => 'dataset',
            ],
            [
                'label' => trans('labels.literature_observations.publication'),
                'value' => 'publication',
            ],
            [
                'label' => trans('labels.literature_observations.is_original_data'),
                'value' => 'is_original_data',
            ],
            [
                'label' => trans('labels.literature_observations.cited_publication'),
                'value' => 'cited_publication',
            ],
            [
                'label' => trans('labels.literature_observations.place_where_referenced_in_publication'),
                'value' => 'place_where_referenced_in_publication',
            ],
            [
                'label' => trans('labels.literature_observations.original_date'),
                'value' => 'original_date',
            ],
            [
                'label' => trans('labels.literature_observations.original_locality'),
                'value' => 'original_locality',
            ],
            [
                'label' => trans('labels.literature_observations.original_elevation'),
                'value' => 'original_elevation',
            ],
            [
                'label' => trans('labels.literature_observations.original_coordinates'),
                'value' => 'original_coordinates',
            ],
            [
                'label' => trans('labels.literature_observations.original_identification'),
                'value' => 'original_identification',
            ],
            [
                'label' => trans('labels.literature_observations.original_identification_validity'),
                'value' => 'original_identification_validity',
            ],
            [
                'label' => trans('labels.literature_observations.other_original_data'),
                'value' => 'other_original_data',
            ],
            [
                'label' => trans('labels.literature_observations.collecting_start_year'),
                'value' => 'collecting_start_year',
            ],
            [
                'label' => trans('labels.literature_observations.collecting_start_month'),
                'value' => 'collecting_start_month',
            ],
            [
                'label' => trans('labels.literature_observations.collecting_end_year'),
                'value' => 'collecting_end_year',
            ],
            [
                'label' => trans('labels.literature_observations.collecting_end_month'),
                'value' => 'collecting_end_month',
            ],
        ]);
    }

    /**
     * Database query to get the data for export.
     *
     * @param  \App\Models\Export  $export
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query(Export $export)
    {
        return LiteratureObservation::with([
            'observation.taxon', 'observation.stage', 'publication', 'citedPublication',
        ])->filter($export->filter)->orderBy('id');
    }

    /**
     * Extract needed data from item.
     *
     * @param  \App\Models\LiteratureObservation  $item
     * @return array
     */
    protected function transformItem($item)
    {
        return [
            'id' => $item->id,
            'taxon' => data_get($item, 'observation.taxon.name'),
            'year' => $item->observation->year,
            'month' => $item->observation->month,
            'day' => $item->observation->day,
            'elevation' => $item->observation->elevation,
            'minimum_elevation' => $item->minimum_elevation,
            'maximum_elevation' => $item->maximum_elevation,
            'latitude' => $item->observation->latitude,
            'longitude' => $item->observation->longitude,
            'mgrs10k' => $item->observation->mgrs10k,
            'location' => $item->observation->location,
            'accuracy' => $item->observation->accuracy,
            'georeferenced_by' => $item->georeferenced_by,
            'georeferenced_date' => optional($item->georeferenced_date)->toDateString(),
            'observer' => $item->observation->observer,
            'identifier' => $item->observation->identifier,
            'note' => $item->observation->note,
            'sex' => $item->observation->sex_translation,
            'number' => $item->observation->number,
            'project' => $item->observation->project,
            'found_on' => $item->observation->found_on,
            'habitat' => $item->observation->habitat,
            'stage' => data_get($item, 'observation.stage.name_translation'),
            'time' => optional($item->time)->format('H:i'),
            'dataset' => $item->observation->dataset,
            'publication' => $item->publication->citation,
            'is_original_data' => $item->is_original_data ? __('Yes') : __('No'),
            'cited_publication' => optional($item->is_original_data ? null : $item->citedPublication)->citation,
            'place_where_referenced_in_publication' => $item->place_where_referenced_in_publication,
            'original_date' => $item->original_date,
            'original_locality' => $item->original_locality,
            'original_elevation' => $item->original_elevation,
            'original_coordinates' => $item->original_coordinates,
            'original_identification' => $item->observation->original_identification,
            'original_identification_validity' => $item->original_identification_validity_translation,
        ];
    }
}
