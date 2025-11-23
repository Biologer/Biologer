<?php

namespace App\Http\Requests;

use App\ActivityLog\LiteratureObservationDiff;
use App\License;
use App\Models\LiteratureObservation;
use App\LiteratureObservationIdentificationValidity;
use App\Rules\Day;
use App\Rules\Decimal;
use App\Rules\Month;
use App\Sex;
use App\Models\Stage;
use App\Support\Dataset;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateLiteratureObservation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'taxon_id' => ['required', 'exists:taxa,id'],
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
            'stage_id' => ['nullable', Rule::in(Stage::pluck('id'))],
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
            'publication_id' => ['required', 'exists:publications,id'],
            'is_original_data' => ['required', 'bool'],
            'cited_publication_id' => [
                'required_if:is_original_data,false', 'nullable', 'exists:publications,id',
            ],
            'original_date' => ['nullable', 'string', 'max:255'],
            'original_locality' => ['nullable', 'string', 'max:255'],
            'original_elevation' => ['nullable', 'string', 'max:255'],
            'original_coordinates' => ['nullable', 'string', 'max:255'],
            'original_identification' => ['required', 'string', 'max:255'],
            'original_identification_validity' => ['required', Rule::in(LiteratureObservationIdentificationValidity::values())],
            'other_original_data' => ['nullable', 'string'],
            'collecting_start_year' => ['nullable', 'integer'],
            'collecting_start_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'collecting_end_year' => ['nullable', 'integer'],
            'collecting_end_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'georeferenced_by' => ['nullable', 'string', 'max:255'],
            'georeferenced_date' => ['nullable', 'string', 'max:255'],
            'place_where_referenced_in_publication' => ['nullable', 'string', 'max:255'],
            'reason' => ['required', 'string', 'max:255'],
        ];
    }

    public function save(LiteratureObservation $literatureObservation)
    {
        return DB::transaction(function () use ($literatureObservation) {
            // Get observation before the update so we can use to compare with
            // updated observation in order to find out what was changed.
            $oldLiteratureObservation = $literatureObservation->load('observation')->replicate();

            // Refresh the relation
            $literatureObservation->load('observation')->observation->update($this->getGeneralObservationData());

            $literatureObservation->update($this->getSpecificObservationData());

            $this->logActivity($literatureObservation, $oldLiteratureObservation);

            return $literatureObservation;
        });
    }

    /**
     * Get observation data specific to field observation from the request.
     *
     * @return array
     */
    protected function getSpecificObservationData()
    {
        return [
            'time' => $this->input('time'),
            'original_date' => $this->input('original_date'),
            'original_locality' => $this->input('original_locality'),
            'original_elevation' => $this->input('original_elevation'),
            'original_coordinates' => $this->input('original_coordinates'),
            'original_identification_validity' => $this->input('original_identification_validity'),
            'other_original_data' => $this->input('other_original_data'),
            'collecting_start_year' => $this->input('collecting_start_year'),
            'collecting_start_month' => $this->input('collecting_start_month'),
            'collecting_end_year' => $this->input('collecting_end_year'),
            'collecting_end_month' => $this->input('collecting_end_month'),
            'georeferenced_by' => $this->input('georeferenced_by'),
            'georeferenced_date' => $this->input('georeferenced_date'),
            'minimum_elevation' => $this->input('minimum_elevation'),
            'maximum_elevation' => $this->input('maximum_elevation'),
            'publication_id' => $this->input('publication_id'),
            'is_original_data' => $this->input('is_original_data'),
            'cited_publication_id' => $this->input('is_original_data') ? null : $this->input('cited_publication_id'),
            'place_where_referenced_in_publication' => $this->input('place_where_referenced_in_publication'),
        ];
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
     * Log update activity for literature observation.
     *
     * @param  \App\Models\LiteratureObservation  $updatedObservation
     * @param  \App\Models\LiteratureObservation  $oldObservation
     * @return void
     */
    protected function logActivity(LiteratureObservation $updatedObservation, LiteratureObservation $oldObservation)
    {
        $changed = LiteratureObservationDiff::changes($updatedObservation, $oldObservation);

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
