<?php

namespace App\Http\Requests;

use App\ActivityLog\TransectSectionDiff;
use App\TransectSection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateTransectSection extends FormRequest
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
     * @param  TransectSection $transectSection
     * @return TransectSection
     * @throws \Throwable
     */
    public function save(TransectSection $transectSection)
    {
        return DB::transaction(function () use ($transectSection) {
            $oldTransectSection = $transectSection->replicate();

            $transectSection->update($this->getTransectSectionData());

            $changed = TransectSectionDiff::changes($transectSection, $oldTransectSection);

            $this->logActivity($transectSection, $changed);

            return $transectSection;
        });
    }

    /**
     * Get transect count observation data specific from the request.
     *
     * @return array
     */
    protected function getTransectSectionData()
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
     * Log update activity for transect count observation.
     *
     * @param  \App\TransectSection  $transectSection
     * @param  array  $beforeChange
     * @return void
     */
    protected function logActivity(TransectSection $transectSection, array $beforeChange)
    {
        activity()->performedOn($transectSection)
           ->causedBy($this->user())
           ->withProperty('old', $beforeChange)
           ->withProperty('reason', $this->input('reason'))
           ->log('updated');
    }
}
