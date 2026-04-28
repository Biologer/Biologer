<?php

namespace App\Http\Requests;

use App\ActivityLog\TransectVisitDiff;
use App\TransectVisit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateTransectVisit extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_time' => ['nullable', 'date_format:H:i:s'],
            'end_time' => ['nullable', 'date_format:H:i:s', 'after:start_time'],
            'cloud_cover' => ['nullable', 'integer', 'min:0', 'max:100'],
            'atmospheric_pressure' => ['nullable', 'numeric', 'min:0'],
            'humidity' => ['nullable', 'integer', 'min:0', 'max:100'],
            'temperature' => ['nullable', 'numeric'],
            'wind_direction' => ['nullable', Rule::in(['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'])],
            'wind_speed' => ['nullable', 'integer', 'min:0', 'max:12'],
            'comments' => ['nullable', 'string'],
            'view_groups_id' => ['required', 'exists:view_groups,id'],
            'transect_sections_id' => ['required', 'exists:transect_sections,id'],
        ];
    }

    /**
     * Store timed count observation and related data.
     *
     * @param  \App\TransectVisit  $transectVisit
     * @return \App\TransectVisit
     */
    public function save(TransectVisit $transectVisit)
    {
        return DB::transaction(function () use ($transectVisit) {
            $oldTransectVisit = $transectVisit->replicate();

            $transectVisit->update($this->getTransectVisitData());

            $changed = TransectVisitDiff::changes($transectVisit, $oldTransectVisit);

            $this->logActivity($transectVisit, $changed);

            return $transectVisit;
        });
    }

    /**
     * Get timed count observation data specific from the request.
     *
     * @return array
     */
    protected function getTransectVisitData()
    {
        return [
            'start_time' => $this->input('start_time'),
            'end_time' => $this->input('end_time'),
            'cloud_cover' => $this->input('cloud_cover'),
            'atmospheric_pressure' => $this->input('atmospheric_pressure'),
            'humidity' => $this->input('humidity'),
            'temperature' => $this->input('temperature'),
            'wind_direction' => $this->input('wind_direction'),
            'wind_speed' => $this->input('wind_speed'),
            'comments' => $this->input('comments'),
            'created_by_id' => $this->user()->id,
            'view_groups_id' => $this->input('view_groups_id'),
            'transect_sections_id' => $this->input('transect_sections_id'),
        ];
    }

    /**
     * Log update activity for timed count observation.
     *
     * @param  \App\TransectVisit  $transectVisit
     * @param  array  $beforeChange
     * @return void
     */
    protected function logActivity(TransectVisit $transectVisit, array $beforeChange)
    {
        activity()->performedOn($transectVisit)
           ->causedBy($this->user())
           ->withProperty('old', $beforeChange)
           ->withProperty('reason', $this->input('reason'))
           ->log('updated');
    }
}
