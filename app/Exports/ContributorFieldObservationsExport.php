<?php

namespace App\Exports;

use App\Export;
use App\FieldObservation;
use Illuminate\Support\Collection;

class ContributorFieldObservationsExport extends BaseExport
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
                'label' => trans('labels.field_observations.taxon'),
                'value' => 'taxon',
            ],
            [
                'label' => trans('labels.field_observations.year'),
                'value' => 'year',
            ],
            [
                'label' => trans('labels.field_observations.month'),
                'value' => 'month',
            ],
            [
                'label' => trans('labels.field_observations.day'),
                'value' => 'day',
            ],
            [
                'label' => trans('labels.field_observations.time'),
                'value' => 'time',
            ],
            [
                'label' => trans('labels.field_observations.latitude'),
                'value' => 'latitude',
            ],
            [
                'label' => trans('labels.field_observations.longitude'),
                'value' => 'longitude',
            ],
            [
                'label' => trans('labels.field_observations.location'),
                'value' => 'location',
            ],
            // [
            //     'label' => trans('labels.field_observations.mgrs10k'),
            //     'value' => 'mgrs10k',
            // ],
            [
                'label' => trans('labels.field_observations.accuracy'),
                'value' => 'accuracy',
            ],
            [
                'label' => trans('labels.field_observations.elevation'),
                'value' => 'elevation',
            ],
            [
                'label' => trans('labels.field_observations.sex'),
                'value' => 'sex',
            ],
            [
                'label' => trans('labels.field_observations.observer'),
                'value' => 'observer',
            ],
            [
                'label' => trans('labels.field_observations.identifier'),
                'value' => 'identifier',
            ],
            [
                'label' => trans('labels.field_observations.stage'),
                'value' => 'stage',
            ],
            [
                'label' => trans('labels.field_observations.data_license'),
                'value' => 'license',
            ],
            [
                'label' => trans('labels.field_observations.number'),
                'value' => 'number',
            ],
            [
                'label' => trans('labels.field_observations.note'),
                'value' => 'note',
            ],
            [
                'label' => trans('labels.field_observations.project'),
                'value' => 'project',
            ],
            [
                'label' => trans('labels.field_observations.found_on'),
                'value' => 'found_on',
            ],
            [
                'label' => trans('labels.field_observations.found_dead'),
                'value' => 'found_dead',
            ],
            [
                'label' => trans('labels.field_observations.found_dead_note'),
                'value' => 'found_dead_note',
            ],
            [
                'label' => trans('labels.field_observations.status'),
                'value' => 'status',
            ],
            [
                'label' => trans('labels.field_observations.types'),
                'value' => 'types',
            ],
        ]);
    }

    /**
     * Perform aditional modifications to available columns if needed.
     * F.e. filter out available columns based on permissions.
     *
     * @param  \Illuminate\Support\Collection  $columns
     * @return \Illuminate\Support\Collection
     */
    protected static function modifyAvailableColumns(Collection $columns)
    {
        return $columns->pipe(function ($columns) {
            if (auth()->user()->hasAnyRole(['admin', 'curator'])) {
                return $columns;
            }

            return $columns->filter(function ($column) {
                return ! in_array($column['value'], ['identifier', 'observer']);
            })->values();
        });
    }

    /**
     * Database query to get the data for export.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query(Export $export)
    {
        return FieldObservation::with([
            'observation.taxon', 'observation.photos', 'observedBy', 'identifiedBy',
            'observation.types.translations', 'observation.stage',
        ])->createdBy($export->user)->filter($export->filter)->orderBy('id');
    }

    /**
     * Extract needed data from item.
     *
     * @param  mixed  $item
     * @return array
     */
    protected function transformItem($item)
    {
        return [
            'id' => $item->id,
            'taxon' => $item->observation->taxon->name,
            'year' => $item->observation->year,
            'month' => $item->observation->month,
            'day' => $item->observation->day,
            'time' => optional($item->time)->format('H:i'),
            'latitude' => $item->observation->latitude,
            'longitude' => $item->observation->longitude,
            'location' => $item->observation->location,
            'mgrs10k' => $item->observation->mgrs10k,
            'accuracy' => $item->observation->accuracy,
            'elevation' => $item->observation->elevation,
            'sex' => $item->observation->sex_translation,
            'observer' => $item->observer,
            'identifier' => $item->identifier,
            'stage' => optional($item->observation->stage)->name_translation,
            'license' => $item->license_translation,
            'number' => $item->observation->number,
            'note' => $item->observation->note,
            'project' => $item->observation->project,
            'found_on' => $item->observation->found_on,
            'found_dead' => $item->found_dead ? __('Yes') : __('No'),
            'found_dead_note' => $item->found_dead_note,
            'status' => $item->status_translation,
            'types' => $item->observation->types->pluck('name')->implode(','),
        ];
    }
}
