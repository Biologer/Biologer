<?php

namespace App\Http\Controllers\Api;

use App\FieldObservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\FieldObservationResource;

class UnidentifiableFieldObservationsBatchController extends Controller
{
    /**
     * Mark multiple field observations as unidentifiable.
     *
     * @return \App\Http\Resources\FieldObservationResource
     */
    public function store()
    {
        request()->validate([
            'field_observation_ids' => ['required', 'array', 'min:1'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $fieldObservations = $this->getFieldObservations();

        $fieldObservations->each(function ($fieldObservation) {
            $this->authorize('markAsUnidentifiable', $fieldObservation);
        });

        $fieldObservations->markAsUnidentifiable();

        $fieldObservations->each(function ($fieldObservation) {
            $this->logActivity($fieldObservation);
        });

        return FieldObservationResource::collection($fieldObservations);
    }

    /**
     * Get field observation to mark unidentifiable.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getFieldObservations()
    {
        return FieldObservation::with([
            'observation.taxon.curators.roles', 'photos',
        ])->whereIn('id', request('field_observation_ids'))->get();
    }

    /**
     * Log marked unidentified activity for field observation.
     *
     * @param  \App\FieldObservation  $fieldObservation
     * @return void
     */
    protected function logActivity(FieldObservation $fieldObservation)
    {
        activity()->performedOn($fieldObservation)
           ->causedBy(auth()->user())
           ->withProperty('reason', request('reason'))
           ->log('marked_unidentifiable');
    }
}
