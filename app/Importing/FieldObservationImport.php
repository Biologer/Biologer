<?php

namespace App\Importing;

use App\Stage;
use App\Taxon;
use App\Import;
use App\Rules\Day;
use App\Observation;
use App\Rules\Month;
use App\FieldObservation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class FieldObservationImport extends BaseImport
{
    /**
     * Definition of all calumns with their labels.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allColumns()
    {
        return collect([
            [
                'label' => trans('labels.field_observations.taxon'),
                'value' => 'taxon',
                'required' => true,
            ],
            [
                'label' => trans('labels.field_observations.year'),
                'value' => 'year',
                'required' => true,
            ],
            [
                'label' => trans('labels.field_observations.month'),
                'value' => 'month',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.day'),
                'value' => 'day',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.latitude'),
                'value' => 'latitude',
                'required' => true,
            ],
            [
                'label' => trans('labels.field_observations.longitude'),
                'value' => 'longitude',
                'required' => true,
            ],
            [
                'label' => trans('labels.field_observations.elevation'),
                'value' => 'elevation',
                'required' => true,
            ],
            [
                'label' => trans('labels.field_observations.accuracy'),
                'value' => 'accuracy',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.location'),
                'value' => 'location',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.time'),
                'value' => 'time',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.note'),
                'value' => 'note',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.found_dead'),
                'value' => 'found_dead',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.found_dead_note'),
                'value' => 'found_dead_note',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.observer'),
                'value' => 'observer',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.identifier'),
                'value' => 'identifier',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.sex'),
                'value' => 'sex',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.number'),
                'value' => 'number',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.project'),
                'value' => 'project',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.found_on'),
                'value' => 'found_on',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.stage'),
                'value' => 'stage',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.original_identification'),
                'value' => 'original_identification',
                'required' => false,
            ],
        ])->pipe(function ($columns) {
            if (auth()->user()->hasAnyRole(['admin', 'curator'])) {
                return $columns;
            }

            return $columns->filter(function ($column) {
                return ! in_array($column['value'], ['identifier', 'observer']);
            });
        });
    }

    /**
     * Make validator instance.
     *
     * @param  array  $data
     * @return \Illuminate\Validation\Validator
     */
    protected function makeValidator(array $data)
    {
        return Validator::make($data, [
            'taxon' => [
                'required',
                Rule::exists('taxa', 'name'),
            ],
            'year' => ['bail', 'required', 'date_format:Y', 'before_or_equal:now'],
            'month' => [
                'bail',
                'nullable',
                'numeric',
                new Month(array_get($data, 'year')),
            ],
            'day' => [
                'bail',
                'nullable',
                'numeric',
                new Day(array_get($data, 'year'), array_get($data, 'month')),
            ],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'elevation' => ['required', 'integer', 'max:10000'],
            'accuracy' => ['nullable', 'integer', 'max:10000'],
            'observer' => ['nullable', 'string'],
            'identifier' => ['nullable', 'string'],
            'stage' => ['nullable', Rule::in(Stage::pluck('name')->all())],
            'sex' => ['nullable', Rule::in(Observation::SEX_OPTIONS)],
            'number' => ['nullable', 'integer', 'min:1'],
            'found_dead' => ['nullable', 'boolean'],
            'found_dead_note' => ['nullable', 'string'],
            'time' => ['nullable', 'date_format:H:i'],
            'project' => ['nullable', 'string', 'max:191'],
            'found_on' => ['nullable', 'string', 'max:191'],
            'note' => ['nullable', 'string'],
            'original_identification' => ['nullable', 'string'],
        ], [
            'year.date_format' => trans('validation.year'),
        ], [
            'taxon' => trans('labels.field_observations.taxon'),
            'year' => trans('labels.field_observations.year'),
            'month' => trans('labels.field_observations.month'),
            'day' => trans('labels.field_observations.day'),
            'latitude' => trans('labels.field_observations.latitude'),
            'longitude' => trans('labels.field_observations.longitude'),
            'elevation' => trans('labels.field_observations.elevation'),
            'accuracy' => trans('labels.field_observations.accuracy'),
            'observer' => trans('labels.field_observations.observer'),
            'identifier' => trans('labels.field_observations.identifier'),
            'stage' => trans('labels.field_observations.stage'),
            'sex' => trans('labels.field_observations.sex'),
            'number' => trans('labels.field_observations.number'),
            'found_dead' => trans('labels.field_observations.found_dead'),
            'found_dead_note' => trans('labels.field_observations.found_dead_note'),
            'time' => trans('labels.field_observations.time'),
            'project' => trans('labels.field_observations.project'),
            'found_on' => trans('labels.field_observations.found_on'),
            'note' => trans('labels.field_observations.note'),
            'original_identification' => trans('labels.field_observations.original_identification'),
        ]);
    }

    /**
     * Store data from single CSV row.
     *
     * @param  \App\Import  $import
     * @param  array  $item
     * @return void
     */
    protected function storeSingleItem(Import $import, array $item)
    {
        $fieldObservation = FieldObservation::create(
            $this->getSpecificObservationData($import, $item)
        );

        $fieldObservation->observation()->save(
            new Observation($this->getGeneralObservationData($import, $item))
        );

        activity()->performedOn($fieldObservation)
            ->causedBy($import->user)
            ->log('created');
    }

    /**
     * Get observation data specific to field observation from the request.
     *
     * @param  \App\Import  $import
     * @param  array  $item
     * @return array
     */
    protected function getSpecificObservationData(Import $import, array $item)
    {
        return [
            'license' => array_get($item, 'data_license', $import->user->settings()->get('data_license')),
            'taxon_suggestion' => array_get($item, 'taxon'),
            'found_dead' => array_get($item, 'found_dead', false),
            'found_dead_note' => array_get($item, 'found_dead', false) ? array_get($item, 'found_dead_note', null) : null,
            'time' => array_get($item, 'time', null),
        ];
    }

    /**
     * Get general observation data from the request.
     *
     * @param  \App\Import  $import
     * @param  array  $item
     * @return array
     */
    protected function getGeneralObservationData(Import $import, array $item)
    {
        return [
            'taxon_id' => optional(Taxon::findByName(array_get($item, 'taxon')))->id,
            'year' => array_get($item, 'year'),
            'month' => array_get($item, 'month'),
            'day' => array_get($item, 'day'),
            'location' => array_get($item, 'location'),
            'latitude' => array_get($item, 'latitude'),
            'longitude' => array_get($item, 'longitude'),
            'mgrs10k' => mgrs10k(array_get($item, 'latitude'), array_get($item, 'longitude')),
            'accuracy' => array_get($item, 'accuracy'),
            'elevation' => array_get($item, 'elevation'),
            'created_by_id' => $import->user_id,
            'observer' => $this->getObserver($import, $item),
            'identifier' => $this->getIdentifier($import, $item),
            'sex' => array_get($item, 'sex'),
            'number' => array_get($item, 'number'),
            'note' => array_get($item, 'note'),
            'project' => array_get($item, 'project'),
            'found_on' => array_get($item, 'found_on'),
            'stage_id' => $this->getStageId($item),
            'original_identification' => array_get($item, 'original_identification', array_get($item, 'taxon')),
        ];
    }

    /**
     * Get observer name.
     *
     * @param  \App\Import  $import
     * @param  array  $data
     * @return string|null
     */
    protected function getObserver(Import $import, array $data)
    {
        return array_get($data, 'observer') && $import->user->hasAnyRole(['admin', 'curator'])
            ? array_get($data, 'observer')
            : $import->user->full_name;
    }

    /**
     * Get identifier name.
     *
     * @param  \App\Import  $import
     * @param  array  $data
     * @return string|null
     */
    protected function getIdentifier(Import $import, array $data)
    {
        if (! $import->user->hasRole(['admin', 'curator'])) {
            return;
        }

        return array_get($data, 'identifier');
    }

    /**
     * Get stage ID.
     *
     * @param  array  $data
     * @return int|null
     */
    protected function getStageId(array $data)
    {
        return optional(Stage::findByName(array_get($data, 'stage', null)))->id;
    }
}
