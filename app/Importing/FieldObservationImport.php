<?php

namespace App\Importing;

use App\DEM\Reader as DEMReader;
use App\FieldObservation;
use App\License;
use App\Observation;
use App\Rules\Day;
use App\Rules\Decimal;
use App\Rules\Month;
use App\Stage;
use App\Support\Dataset;
use App\Taxon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FieldObservationImport extends BaseImport
{
    /**
     * @var \App\DEM\Reader
     */
    protected $demReader;

    /**
     * Create new importer instance.
     *
     * @param  \App\Import  $import
     * @param  \App\DEM\Reader  $demReader
     * @return void
     */
    public function __construct($import, DEMReader $demReader)
    {
        parent::__construct($import);

        $this->setDEMReader($demReader);
    }

    /**
     * Set DEM reader instance to get missing elevation.
     *
     * @param  \App\DEM\Reader  $demReader
     * @return self
     */
    public function setDEMReader(DEMReader $demReader)
    {
        $this->demReader = $demReader;

        return $this;
    }

    /**
     * Definition of all calumns with their labels.
     *
     * @param  \App\User|null  $user
     * @return \Illuminate\Support\Collection
     */
    public static function columns($user = null)
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
                'required' => false,
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
                'label' => trans('labels.field_observations.habitat'),
                'value' => 'habitat',
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
            [
                'label' => trans('labels.field_observations.dataset'),
                'value' => 'dataset',
                'required' => false,
            ],
            [
                'label' => trans('labels.field_observations.data_license'),
                'value' => 'license',
                'required' => true,
            ],
        ])->pipe(function ($columns) use ($user) {
            if (! $user || optional($user)->hasAnyRole(['admin', 'curator'])) {
                return $columns;
            }

            return $columns->filter(function ($column) {
                return ! in_array($column['value'], ['identifier', 'observer']);
            })->values();
        });
    }

    /**
     * Get validation rules specific for import type.
     *
     * @return array
     */
    public static function specificValidationRules()
    {
        return [
            'options.approve_curated' => ['nullable', 'boolean'],
        ];
    }

    public function generateErrorsRoute()
    {
        return route('api.field-observation-imports.errors', $this->model());
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
                new Month(Arr::get($data, 'year')),
            ],
            'day' => [
                'bail',
                'nullable',
                'numeric',
                new Day(Arr::get($data, 'year'), Arr::get($data, 'month')),
            ],
            'latitude' => ['required', new Decimal(['min' => -90, 'max' => 90])],
            'longitude' => ['required', new Decimal(['min' => -180, 'max' => 180])],
            'elevation' => ['nullable', 'integer', 'max:10000'],
            'accuracy' => ['nullable', 'integer', 'max:10000'],
            'observer' => ['nullable', 'string'],
            'identifier' => ['nullable', 'string'],
            'stage' => ['nullable', Rule::in(Stage::pluck('name'))],
            'sex' => ['nullable', Rule::in(Observation::SEX_OPTIONS)],
            'number' => ['nullable', 'integer', 'min:1'],
            'found_dead' => ['nullable', 'string', Rule::in($this->yesNo())],
            'found_dead_note' => ['nullable', 'string', 'max:1000'],
            'time' => ['nullable', 'date_format:H:i'],
            'project' => ['nullable', 'string', 'max:191'],
            'habitat' => ['nullable', 'string', 'max:191'],
            'found_on' => ['nullable', 'string', 'max:191'],
            'note' => ['nullable', 'string'],
            'original_identification' => ['nullable', 'string'],
            'dataset' => ['nullable', 'string'],
            'license' => ['nullable', 'string', Rule::in(License::allActive()->pluck('name'))],
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
            'habitat' => trans('labels.field_observations.habitat'),
            'found_on' => trans('labels.field_observations.found_on'),
            'note' => trans('labels.field_observations.note'),
            'original_identification' => trans('labels.field_observations.original_identification'),
            'dataset' => trans('labels.field_observations.dataset'),
            'license' => trans('labels.field_observations.data_license'),
        ]);
    }

    /**
     * "Yes" and "No" options translated in language the import is using.
     *
     * @return array
     */
    protected function yesNo()
    {
        $lang = $this->model()->lang;

        return [__('Yes', [], $lang), __('No', [], $lang)];
    }

    /**
     * Store data from single CSV row.
     *
     * @param  array  $item
     * @return void
     */
    protected function storeSingleItem(array $item)
    {
        $fieldObservation = FieldObservation::create(
            $this->getSpecificObservationData($item)
        );

        $observation = $fieldObservation->observation()->save(
            new Observation($this->getGeneralObservationData($item))
        );

        activity()->performedOn($fieldObservation)
            ->causedBy($this->model()->user)
            ->log('created');

        if ($observation->isApproved()) {
            activity()->performedOn($fieldObservation)
                ->causedBy($this->model()->user)
                ->log('approved');
        }
    }

    /**
     * Get observation data specific to field observation from the request.
     *
     * @param  array  $item
     * @return array
     */
    protected function getSpecificObservationData(array $item)
    {
        return [
            'license' => Arr::get($item, 'data_license') ?: $this->model()->user->settings()->get('data_license'),
            'taxon_suggestion' => Arr::get($item, 'taxon') ?: null,
            'found_dead' => $this->getFoundDead($item),
            'found_dead_note' => $this->getFoundDead($item) ? Arr::get($item, 'found_dead_note') : null,
            'time' => Arr::get($item, 'time') ?: null,
            'observed_by_id' => $this->getObserverId($item),
            'identified_by_id' => $this->getIdentifierId($item),
            'license' => $this->getLicense($item),
        ];
    }

    /**
     * Get general observation data from the request.
     *
     * @param  array  $item
     * @return array
     */
    protected function getGeneralObservationData(array $item)
    {
        $latitude = $this->getLatitude($item);
        $longitude = $this->getLongitude($item);
        $taxon = $this->getTaxon($item);

        return [
            'taxon_id' => $taxon ? $taxon->id : null,
            'year' => Arr::get($item, 'year'),
            'month' => Arr::get($item, 'month') ?: null,
            'day' => Arr::get($item, 'day') ?: null,
            'location' => Arr::get($item, 'location') ?: null,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'mgrs10k' => mgrs10k($latitude, $longitude),
            'accuracy' => Arr::get($item, 'accuracy') ?: null,
            'elevation' => $this->getElevation($item),
            'created_by_id' => $this->model()->for_user_id ?: $this->model()->user_id,
            'observer' => $this->getObserver($item),
            'identifier' => $this->getIdentifier($item),
            'sex' => Arr::get($item, 'sex') ?: null,
            'number' => Arr::get($item, 'number') ?: null,
            'note' => Arr::get($item, 'note') ?: null,
            'project' => Arr::get($item, 'project') ?: null,
            'habitat' => Arr::get($item, 'habitat') ?: null,
            'found_on' => Arr::get($item, 'found_on') ?: null,
            'stage_id' => $this->getStageId($item),
            'original_identification' => Arr::get($item, 'original_identification', Arr::get($item, 'taxon')),
            'dataset' => Arr::get($item, 'dataset') ?? Dataset::default(),
            'approved_at' => $this->getApprovedAt($taxon),
        ];
    }

    /**
     * Get ID of taxon using it's name.
     *
     * @param  array  $data
     * @return \App\Taxon|null
     */
    protected function getTaxon(array $data)
    {
        return Taxon::findByName(Arr::get($data, 'taxon'));
    }

    /**
     * Get latitude.
     *
     * @param  array  $data
     * @return float
     */
    protected function getLatitude(array $data)
    {
        return (float) str_replace(',', '.', Arr::get($data, 'latitude'));
    }

    /**
     * Get longitude.
     *
     * @param  array  $data
     * @return float
     */
    protected function getLongitude(array $data)
    {
        return (float) str_replace(',', '.', Arr::get($data, 'longitude'));
    }

    /**
     * Get elevation.
     *
     * @param  array  $data
     * @return |int|null
     */
    protected function getElevation(array $data)
    {
        $elevation = Arr::get($data, 'elevation');

        if (is_numeric($elevation)) {
            return $elevation;
        }

        if ($this->demReader) {
            return $this->demReader->getElevation(
                $this->getLatitude($data),
                $this->getLongitude($data)
            );
        }
    }

    /**
     * Get observer name.
     *
     * @param  array  $data
     * @return string|null
     */
    protected function getObserver(array $data)
    {
        if (! $this->model()->user->hasAnyRole(['admin', 'curator'])) {
            return $this->model()->user->full_name;
        }

        return Arr::get($data, 'observer') ?: $this->model()->user->full_name;
    }

    /**
     * Get observer ID.
     *
     * @param  array  $data
     * @return int
     */
    protected function getObserverId(array $data)
    {
        if ($this->shouldUseCurrentUserId(Arr::get($data, 'observer'))) {
            return $this->model()->user->id;
        }
    }

    /**
     * Get identifier name.
     *
     * @param  array  $data
     * @return string|null
     */
    protected function getIdentifier(array $data)
    {
        if (! $this->model()->user->hasAnyRole(['admin', 'curator'])) {
            return $this->model()->user->full_name;
        }

        return Arr::get($data, 'identifier') ?: $this->model()->user->full_name;
    }

    /**
     * Get identifier ID.
     *
     * @param  array  $data
     * @return int
     */
    protected function getIdentifierId(array $data)
    {
        if ($this->shouldUseCurrentUserId(Arr::get($data, 'identifier'))) {
            return $this->model()->user->id;
        }
    }

    /**
     * Check if the name matches current user.
     *
     * @param  string|null  $name
     * @return bool
     */
    private function shouldUseCurrentUserId($name = null)
    {
        return ! $this->model()->user->hasAnyRole(['admin', 'curator']) || ! $name;
    }

    /**
     * Get stage ID.
     *
     * @param  array  $data
     * @return int|null
     */
    protected function getStageId(array $data)
    {
        return optional(Stage::findByName(strtolower(Arr::get($data, 'stage', ''))))->id;
    }

    /**
     * Get value for "Found Dead" field.
     *
     * @param  array  $data
     * @return bool
     */
    protected function getFoundDead(array $data)
    {
        $value = Arr::get($data, 'found_dead', false);

        return $this->isTranslatedYes($value) || filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Check if the value matches with "Yes" translation.
     *
     * @param string $value
     * @return bool
     */
    protected function isTranslatedYes($value)
    {
        if (! is_string($value)) {
            return false;
        }

        $yes = __('Yes', [], $this->model()->lang);

        return strtolower($yes) === strtolower($value);
    }

    /**
     * Get license for the observation.
     *
     * @param  array  $data
     * @return int
     */
    protected function getLicense(array $data)
    {
        return ($license = Arr::get($data, 'license'))
            ? License::findByName($license)->id
            : $this->model()->user->settings()->get('data_license');
    }

    /**
     * Get `approved_at` attribute for the observation.
     *
     * @param \App\Taxon|null $taxon
     * @return \Carbon\Carbon|null
     */
    protected function getApprovedAt($taxon)
    {
        return $this->shouldApprove($taxon) ? now() : null;
    }

    /**
     * Check if we should automatically approve observation of given taxon.
     *
     * @param \App\Taxon|null $taxon
     * @return bool
     */
    protected function shouldApprove($taxon)
    {
        return $this->shouldApproveCurated() &&
            $this->model()->user->hasRole('curator') &&
            $taxon && $taxon->canBeApprovedBy($this->model()->user);
    }

    /**
     * Check if option to verify observations of curated taxa is selected.
     *
     * @return bool
     */
    protected function shouldApproveCurated()
    {
        return $this->model()->options['approve_curated'] ?? false;
    }
}
