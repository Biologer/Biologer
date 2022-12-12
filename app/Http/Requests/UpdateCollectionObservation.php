<?php

namespace App\Http\Requests;

use App\ActivityLog\CollectionObservationDiff;
use App\CollectionObservation;
use App\License;
use App\ObservationIdentificationValidity;
use App\Rules\Day;
use App\Rules\Decimal;
use App\Rules\Month;
use App\Sex;
use App\Support\Dataset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateCollectionObservation extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'taxon_id' => ['required', Rule::exists('taxa', 'id')],
            'year' => ['bail', 'nullable', 'date_format:Y', 'before_or_equal:now'],
            'month' => [
                'bail', 'nullable', 'numeric', new Month($this->input('year')),
            ],
            'day' => [
                'bail', 'nullable', 'numeric', new Day($this->input('year'), $this->input('month')),
            ],
            'latitude' => ['required', new Decimal(['min' => -90, 'max' => 90])],
            'longitude' => ['required', new Decimal(['min' => -180, 'max' => 180])],
            'elevation' => ['required', 'integer', 'max:10000'],
            'accuracy' => ['nullable', 'integer', 'max:500000'],
            'observer' => ['nullable', 'string'],
            'identifier' => ['nullable', 'string'],
            'stage_id' => ['nullable', Rule::exists('stages', 'id')],
            'sex' => ['nullable', Rule::in(Sex::options()->keys())],
            'number' => ['nullable', 'integer', 'min:1'],
            'photos' => ['nullable', 'array', 'max:'.config('biologer.photos_per_observation')],
            'time' => ['nullable', 'date_format:H:i'],
            'project' => ['nullable', 'string', 'max:191'],
            'found_on' => ['nullable', 'string', 'max:191'],
            'habitat' => ['nullable', 'string', 'max:191'],
            'note' => ['nullable', 'string'],
            'observed_by_id' => ['nullable', Rule::exists('users', 'id')],
            'identified_by_id' => ['nullable', Rule::exists('users', 'id')],
            'dataset' => ['nullable', 'string', 'max:255'],
            'data_license' => ['nullable', Rule::in(License::ids())],
            'minimum_elevation' => [
                'nullable', 'integer', 'max:10000', 'lte:maximum_elevation', 'lte:elevation',
            ],
            'maximum_elevation' => [
                'nullable', 'integer', 'max:10000', 'gte:minimum_elevation', 'gte:elevation',
            ],
            'collection_id' => ['required', Rule::exists('specimen_collections', 'id')],
            'original_date' => ['nullable', 'string', 'max:255'],
            'original_locality' => ['nullable', 'string', 'max:255'],
            'original_elevation' => ['nullable', 'string', 'max:255'],
            'original_coordinates' => ['nullable', 'string', 'max:255'],
            'original_identification' => ['required', 'string', 'max:255'],
            'original_identification_validity' => ['required', Rule::in(ObservationIdentificationValidity::values())],
            'verbatim_tag' => ['nullable', 'string'],
            'collecting_start_year' => ['nullable', 'integer'],
            'collecting_start_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'collecting_end_year' => ['nullable', 'integer'],
            'collecting_end_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'georeferenced_by' => ['nullable', 'string', 'max:255'],
            'georeferenced_date' => ['nullable', 'string', 'max:255'],
            'collector' => ['nullable', 'string', 'max:191'],
            'catalogue_number' => ['nullable', 'string', 'max:191'],
            'cabinet_number' => ['nullable', 'string', 'max:191'],
            'box_number' => ['nullable', 'string', 'max:191'],
            'disposition' => ['nullable', 'string', 'max:191'],
            'preparator' => ['nullable', 'string', 'max:191'],
            'preparation_method' => ['nullable', 'string', 'max:191'],
            'type_status' => ['nullable', 'string', 'max:191'],
            'reason' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(CollectionObservation $collectionObservation)
    {
        return DB::transaction(function () use ($collectionObservation) {
            // Get observation before the update so we can use to compare with
            // updated observation in order to find out what was changed.
            $oldCollectionObservation = $collectionObservation->load('observation')->replicate();

            // Refresh the relation
            $collectionObservation->load('observation')->observation->update($this->getGeneralObservationData());

            $collectionObservation->syncPhotos(
                collect($this->input('photos', [])),
                $this->user()->settings()->get('image_license')
            );

            $collectionObservation->update($this->getSpecificObservationData());

            $this->logActivity($collectionObservation, $oldCollectionObservation);

            return $collectionObservation;
        });
    }

    /**
     * Get observation data specific to field observation from the request.
     *
     * @return array
     */
    protected function getSpecificObservationData()
    {
        return $this->only([
            'time',
            'original_date',
            'original_locality',
            'original_elevation',
            'original_coordinates',
            'original_identification_validity',
            'verbatim_tag',
            'collecting_start_year',
            'collecting_start_month',
            'collecting_end_year',
            'collecting_end_month',
            'georeferenced_by',
            'georeferenced_date',
            'minimum_elevation',
            'maximum_elevation',
            'collection_id',
            'collector',
            'catalogue_number',
            'cabinet_number',
            'box_number',
            'disposition',
            'preparator',
            'preparation_method',
            'type_status',
        ]);
    }

    /**
     * Get general observation data from the request.
     *
     * @return array
     */
    protected function getGeneralObservationData()
    {
        $latitude = (float) str_replace(',', '.', $this->input('latitude'));
        $longitude = (float) str_replace(',', '.', $this->input('longitude'));

        return [
            'taxon_id' => $this->input('taxon_id'),
            'year' => $this->input('year'),
            'month' => $this->input('month') ? (int) $this->input('month') : null,
            'day' => $this->input('day') ? (int) $this->input('day') : null,
            'location' => $this->input('location'),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'mgrs10k' => mgrs10k($latitude, $longitude),
            'accuracy' => $this->input('accuracy'),
            'elevation' => $this->input('elevation'),
            'observer' => $this->input('observer'),
            'identifier' => $this->input('identifier'),
            'sex' => $this->input('sex'),
            'stage_id' => $this->input('stage_id'),
            'number' => $this->input('number'),
            'note' => $this->input('note'),
            'project' => $this->input('project'),
            'found_on' => $this->input('found_on'),
            'habitat' => $this->input('habitat'),
            'original_identification' => $this->input('original_identification'),
            'dataset' => $this->input('dataset') ?? Dataset::default(),
        ];
    }

    /**
     * Log update activity for collection observation.
     *
     * @param  \App\CollectionObservation  $updatedObservation
     * @param  \App\CollectionObservation  $oldObservation
     * @return void
     */
    protected function logActivity(CollectionObservation $updatedObservation, CollectionObservation $oldObservation)
    {
        $changed = CollectionObservationDiff::changes($updatedObservation, $oldObservation);

        // Nothing was changed so we won't log anything.
        if (empty($changed)) {
            return;
        }

        activity()->performedOn($updatedObservation)
            ->causedBy($this->user())
            ->withProperty('old', $changed)
            ->withProperty('reason', $this->input('reason'))
            ->log('updated');
    }
}
