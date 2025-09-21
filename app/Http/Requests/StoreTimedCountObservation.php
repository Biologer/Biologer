<?php

namespace App\Http\Requests;

use App\Rules\Day;
use App\Rules\Month;
use App\TimedCountObservation;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreTimedCountObservation extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
            'start_time' => ['nullable', 'date_format:H:i:s'],
            'end_time' => ['nullable', 'date_format:H:i:s', 'after:start_time'],
            'count_duration' => ['nullable', 'integer', 'min:0'],
            'cloud_cover' => ['nullable', 'integer', 'min:0', 'max:100'],
            'atmospheric_pressure' => ['nullable', 'numeric', 'min:0'],
            'humidity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'temperature' => ['nullable', 'numeric'],
            'wind_direction' => ['nullable', Rule::in(['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'])],
            'wind_speed' => ['nullable', 'integer', 'min:0', 'max:12'],
            'habitat' => ['nullable', 'string'],
            'area' => ['nullable', 'integer', 'min:0'],
            'route_length' => ['nullable', 'integer', 'min:0'],
            'observer' => ['nullable', 'string'],
            'observed_by_id' => ['nullable', 'exists:users,id'],
            'view_groups_id' => ['required', 'exists:view_groups,id'],
        ];
    }

    /**
     * Store timed count observation and related data.
     *
     * @return \App\TimedCountObservation
     */
    public function store()
    {
        return DB::transaction(function () {
            return tap($this->createTimedCountObservation(), function ($timedCountObservation) {
                $this->logActivity($timedCountObservation);
            });
        });
    }

    /**
     * Create timed count observation.
     *
     * @return \App\TimedCountObservation
     */
    protected function createTimedCountObservation()
    {
        $timedCountObservation = TimedCountObservation::create($this->getTimedCountData());

        return $timedCountObservation;
    }

    /**
     * Get timed count observation data specific from the request.
     *
     * @return array
     */
    protected function getTimedCountData()
    {
        return [
            'year' => $this->input('year'),
            'month' => $this->input('month') ? (int) $this->input('month') : null,
            'day' => $this->input('day') ? (int) $this->input('day') : null,
            'start_time' => $this->input('start_time'),
            'end_time' => $this->input('end_time'),
            'count_duration' => $this->input('count_duration'),
            'cloud_cover' => $this->input('cloud_cover'),
            'atmospheric_pressure' => $this->input('atmospheric_pressure'),
            'humidity' => $this->input('humidity'),
            'temperature' => $this->input('temperature'),
            'wind_direction' => $this->input('wind_direction'),
            'wind_speed' => $this->input('wind_speed'),
            'habitat' => $this->input('habitat'),
            'comments' => $this->input('comments'),
            'area' => $this->input('area'),
            'route_length' => $this->input('route_length'),
            'observer' => $this->getObserver(),

            'observed_by_id' => $this->getObservedBy(),
            'created_by_id' => $this->user()->id,
            'view_groups_id' => $this->input('view_groups_id'),
        ];
    }

    /**
     * Get ID of observer name.
     *
     * @return string|null
     */
    protected function getObservedBy()
    {
        if (! $this->user()->hasAnyRole(['admin', 'curator'])) {
            return $this->user()->id;
        }

        if ($this->input('observed_by_id')) {
            return $this->input('observed_by_id');
        }

        if (! $this->input('observer')) {
            return $this->user()->id;
        }
    }

    /**
     * Get observer name.
     *
     * @return string|null
     */
    protected function getObserver()
    {
        if ($this->getObservedBy()) {
            return User::find($this->getObservedBy())->full_name;
        }

        return $this->input('observer');
    }

    /**
     * Log created activity for timed count observation.
     *
     * @param  \App\TimedCountObservation  $timedCountObservation
     * @return void
     */
    protected function logActivity(TimedCountObservation $timedCountObservation)
    {
        activity()->performedOn($timedCountObservation)
            ->causedBy($this->user())
            ->log('created');
    }

}
