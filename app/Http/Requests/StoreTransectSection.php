<?php

namespace App\Http\Requests;

use App\TransectCountObservation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreTransectSection extends FormRequest
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
            'length' => ['nullable', 'string'],
            'primary_habitat' => ['nullable', 'string'],
            'secondary_habitat' => ['nullable', 'string'],
            'land_tenure' => ['nullable', 'string'],
            'land_management' => ['nullable', 'string'],
            'transect_count_observation_id' => ['required', 'exists:transect_count_observations,id'],
        ];
    }

    /**
     * Store transect count observation and related data.
     *
     * @return \App\TransectCountObservation
     */
    public function store()
    {
        return DB::transaction(function () {
            return tap($this->createTransectCountObservation(), function ($transectCountObservation) {
                $this->logActivity($transectCountObservation);
            });
        });
    }

    /**
     * Create transect count observation.
     *
     * @return \App\TransectCountObservation
     */
    protected function createTransectCountObservation()
    {
        $transectCountObservation = TransectCountObservation::create($this->getTransectCountData());

        return $transectCountObservation;
    }

    /**
     * Get transect count observation data specific from the request.
     *
     * @return array
     */
    protected function getTransectCountData()
    {
        return [
            'name' => $this->input('name'),
            'description' => $this->input('description'),
            'primary_habitat' => $this->input('primary_habitat'),
            'secondary_habitat' => $this->input('secondary_habitat'),
            'land_tenure' => $this->input('land_tenure'),
            'land_management' => $this->input('land_management'),
            'created_by_id' => $this->user()->id,
            'transect_count_observation_id' => $this->input('transect_count_observation_id'),
        ];
    }

    /**
     * Log created activity for transect count observation.
     *
     * @param  TransectCountObservation $transectCountObservation
     * @return void
     */
    protected function logActivity(TransectCountObservation $transectCountObservation)
    {
        activity()->performedOn($transectCountObservation)
            ->causedBy($this->user())
            ->log('created');
    }

}
