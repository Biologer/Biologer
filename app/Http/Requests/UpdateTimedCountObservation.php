<?php

namespace App\Http\Requests;

use App\ActivityLog\TimedCountObservationDiff;
use App\Notifications\TimedCountObservationEdited;
use App\Rules\Day;
use App\Rules\Month;
use App\Models\TimedCountObservation;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateTimedCountObservation extends FormRequest
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
     * @param  \App\Models\TimedCountObservation  $timedCountObservation
     * @return \App\Models\TimedCountObservation
     */
    public function save(TimedCountObservation $timedCountObservation)
    {
        return DB::transaction(function () use ($timedCountObservation) {
            $oldTimedCountObservation = $timedCountObservation->replicate();

            $timedCountObservation->update($this->getTimedCountObservationData());

            $changed = TimedCountObservationDiff::changes($timedCountObservation, $oldTimedCountObservation);

            $this->logActivity($timedCountObservation, $changed);

            $this->notifyCreator($timedCountObservation);

            return $timedCountObservation;
        });
    }

    /**
     * Get timed count observation data specific from the request.
     *
     * @return array
     */
    protected function getTimedCountObservationData()
    {
        $data = [
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

            'view_groups_id' => $this->input('view_groups_id'),
        ];

        if ($this->user()->hasAnyRole(['admin', 'curator'])) {
            $data['observed_by_id'] = $this->input('observed_by_id');
        }

        return $data;
    }

    /**
     * Log update activity for timed count observation.
     *
     * @param  \App\Models\TimedCountObservation  $timedCountObservation
     * @param  array  $beforeChange
     * @return void
     */
    protected function logActivity(TimedCountObservation $timedCountObservation, array $beforeChange)
    {
        activity()->performedOn($timedCountObservation)
           ->causedBy($this->user())
           ->withProperty('old', $beforeChange)
           ->withProperty('reason', $this->input('reason'))
           ->log('updated');
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

        return optional(User::find($this->input('observed_by_id')))->full_name ?? $this->input('observer');
    }

    /**
     * Send notification to creator of observation that it has been updated.
     *
     * @param  \App\Models\TimedCountObservation  $timedCountObservation
     * @return void
     */
    private function notifyCreator(TimedCountObservation $timedCountObservation)
    {
        // We don't want to send notification if the user is changing their own observation.
        if ($this->user()->is($timedCountObservation->creator)) {
            return;
        }

        optional($timedCountObservation->creator)->notify(
            new TimedCountObservationEdited($timedCountObservation, $this->user())
        );
    }
}
