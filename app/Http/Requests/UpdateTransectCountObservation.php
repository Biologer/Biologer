<?php

namespace App\Http\Requests;

use App\ActivityLog\TransectCountObservationDiff;
use App\TransectCountObservation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateTransectCountObservation extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['string'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'length' => ['nullable', 'string'],
            'primary_habitat' => ['nullable', 'string'],
        ];
    }

    /**
     * Store transect count observation and related data.
     *
     * @param  \App\TransectCountObservation  $transectCountObservation
     * @return \App\TransectCountObservation
     */
    public function save(TransectCountObservation $transectCountObservation)
    {
        return DB::transaction(function () use ($transectCountObservation) {
            $oldTransectCountObservation = $transectCountObservation->replicate();

            $transectCountObservation->update($this->getTransectCountObservationData());

            $changed = TransectCountObservationDiff::changes($transectCountObservation, $oldTransectCountObservation);

            $this->logActivity($transectCountObservation, $changed);

            return $transectCountObservation;
        });
    }

    /**
     * Get transect count observation data specific from the request.
     *
     * @return array
     */
    protected function getTransectCountObservationData()
    {
        return [
            'name' => $this->input('name'),
            'description' => $this->input('description'),
            'location' => $this->input('location'),
            'length' => $this->input('length'),
            'primary_habitat' => $this->input('primary_habitat'),
        ];
    }

    /**
     * Log update activity for transect count observation.
     *
     * @param  \App\TransectCountObservation  $transectCountObservation
     * @param  array  $beforeChange
     * @return void
     */
    protected function logActivity(TransectCountObservation $transectCountObservation, array $beforeChange)
    {
        activity()->performedOn($transectCountObservation)
           ->causedBy($this->user())
           ->withProperty('old', $beforeChange)
           ->withProperty('reason', $this->input('reason'))
           ->log('updated');
    }
}
