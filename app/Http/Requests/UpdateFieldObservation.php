<?php

namespace App\Http\Requests;

use App\User;
use App\Stage;
use App\Taxon;
use App\License;
use App\Rules\Day;
use App\Observation;
use App\Rules\Month;
use App\ObservationType;
use App\FieldObservation;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\ActivityLog\FieldObservationLog;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldObservation extends FormRequest
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
            'taxon_id' => ['nullable', 'exists:taxa,id'],
            'taxon_suggestion' => ['nullable', 'string', 'max:255'],
            'year' => ['bail', 'required', 'date_format:Y', 'before_or_equal:now'],
            'month' => [
                'bail',
                'nullable',
                'numeric',
                new Month($this->input('year')),
            ],
            'day' => [
                'bail',
                'nullable',
                'numeric',
                new Day($this->input('year'), $this->input('month')),
            ],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'elevation' => ['required', 'integer', 'max:10000'],
            'accuracy' => ['nullable', 'integer', 'max:10000'],
            'observer' => ['nullable', 'string'],
            'identifier' => ['nullable', 'string'],
            'sex' => ['nullable', Rule::in(Observation::SEX_OPTIONS)],
            'stage_id' => ['nullable', Rule::in(Stage::pluck('id')->all())],
            'number' => ['nullable', 'integer', 'min:1'],
            'found_dead' => ['nullable', 'boolean'],
            'found_dead_note' => ['nullable'],
            'data_license' => ['nullable', Rule::in(License::ids()->all())],
            'photos' => ['nullable', 'array', 'max:'.config('biologer.photos_per_observation')],
            'photos.*.id' => ['required_without:photos.*.path', 'integer'],
            'photos.*.path' => ['required_without:photos.*.id', 'string'],
            'photos.*.crop' => ['nullable', 'array'],
            'photos.*.crop.x' => ['required_with:photos.*.crop', 'integer'],
            'photos.*.crop.y' => ['required_with:photos.*.crop', 'integer'],
            'photos.*.crop.width' => ['required_with:photos.*.crop', 'integer'],
            'photos.*.crop.height' => ['required_with:photos.*.crop', 'integer'],
            'photos.*.license' => ['nullable', Rule::in(License::ids()->all())],
            'time' => ['nullable' ,'date_format:H:i'],
            'project' => ['nullable', 'string', 'max:191'],
            'found_on' => ['nullable', 'string', 'max:191'],
            'note' => ['nullable', 'string'],
            'reason' => ['required', 'string', 'max:255'],
            'observation_types_ids' => [
                'nullable', 'array', Rule::in(ObservationType::pluck('id')->all()),
            ],
            'observed_by_id' => ['nullable', Rule::exists('users', 'id')],
            'identified_by_id' => ['nullable', Rule::exists('users', 'id')],
        ];
    }

    /**
     * Store observation and related data.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return \App\FieldObservation
     */
    public function save(FieldObservation $fieldObservation)
    {
        return DB::transaction(function () use ($fieldObservation) {
            $oldData = $fieldObservation->toArray();

            $fieldObservation->update($this->getSpecificObservationData());
            $fieldObservation->observation->update($this->getGeneralObservationData());
            $photoSync = $fieldObservation->syncPhotos(
                collect($this->input('photos', [])),
                $this->user()->settings()->get('image_license')
            );

            $this->syncRelations($fieldObservation);

            $beforeUpdate = FieldObservationLog::changes($fieldObservation, $oldData, $photoSync);

            // Log activity and move to pending only if something more than
            // updating photo license occurred.
            if (! empty($beforeUpdate)) {
                $this->logActivity($fieldObservation, $beforeUpdate);

                $fieldObservation->moveToPending();
            }

            return $fieldObservation;
        });
    }

    /**
     * Get observation data specific to field observation from the request.
     *
     * @return array
     */
    protected function getSpecificObservationData()
    {
        $data = [
            'found_dead' => $this->input('found_dead', false),
            'found_dead_note' => $this->input('found_dead', false) ? $this->input('found_dead_note') : null,
            'license' => $this->input('data_license') ?: $this->user()->settings()->get('data_license'),
            'taxon_suggestion' => $this->input('taxon_id')
                ? Taxon::find($this->input('taxon_id'))->name
                : $this->input('taxon_suggestion'),
            'time' => $this->input('time'),
        ];

        if ($this->user()->hasAnyRole(['admin', 'curator'])) {
            $data['observed_by_id'] = $this->input('observed_by_id');
            $data['identified_by_id'] = $this->input('identified_by_id');
        }

        return $data;
    }

    /**
     * Get general observation data from the request.
     *
     * @return array
     */
    protected function getGeneralObservationData()
    {
        $data = [
            'taxon_id' => $this->input('taxon_id'),
            'year' => $this->input('year'),
            'month' => $this->input('month') ? (int) $this->input('month') : null,
            'day' => $this->input('day') ? (int) $this->input('day') : null,
            'location' => $this->input('location'),
            'latitude' => $this->input('latitude'),
            'longitude' => $this->input('longitude'),
            'mgrs10k' => mgrs10k($this->input('latitude'), $this->input('longitude')),
            'accuracy' => $this->input('accuracy'),
            'elevation' => $this->input('elevation'),
            'sex' => $this->input('sex'),
            'number' => $this->input('number'),
            'stage_id' => $this->input('stage_id'),
            'project' => $this->input('project'),
            'found_on' => $this->input('found_on'),
            'note' => $this->input('note'),
        ];

        if ($this->user()->hasAnyRole(['admin', 'curator'])) {
            $data['observer'] = $this->getObserver();
            $data['identifier'] = $this->getIdentifier();
        }

        return $data;
    }

    /**
     * Log update activity for field observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @param  array  $beforeChange
     * @return void
     */
    protected function logActivity(FieldObservation $fieldObservation, array $beforeChange)
    {
        activity()->performedOn($fieldObservation)
           ->causedBy($this->user())
           ->withProperty('old', $beforeChange)
           ->withProperty('reason', $this->input('reason'))
           ->log('updated');
    }

    /**
     * Sync field observation relations.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return void
     */
    protected function syncRelations(FieldObservation $fieldObservation)
    {
        $fieldObservation->observation->types()->sync($this->input('observation_types_ids', []));
    }

    /**
     * Get observer name.
     *
     * @return string|null
     */
    protected function getObserver()
    {
        if (! $this->input('observed_by_id')) {
            return $this->input('observer');
        }

        return optional(User::find($this->input('observed_by_id')))->full_name
            ?? $this->input('observer');
    }

    /**
     * Get identifier name.
     *
     * @return string|null
     */
    protected function getIdentifier()
    {
        if (! $this->input('identified_by_id')) {
            return $this->input('identifier');
        }

        return optional(User::find($this->input('identified_by_id')))->full_name
            ?? $this->input('identifier');
    }
}
