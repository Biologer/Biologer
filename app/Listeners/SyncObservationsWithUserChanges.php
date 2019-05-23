<?php

namespace App\Listeners;

use App\FieldObservation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncObservationsWithUserChanges implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

        FieldObservation::with('observation')
            ->where('observed_by_id', $user->id)
            ->chunk(500, function ($fieldObservations) use ($user) {
                $fieldObservations->each(function ($fieldObservation) use ($user) {
                    $fieldObservation->observation->update(['observer' => $user->full_name]);
                });
            });

        FieldObservation::with('observation')
            ->where('identified_by_id', $user->id)
            ->chunk(500, function ($fieldObservations) use ($user) {
                $fieldObservations->each(function ($fieldObservation) use ($user) {
                    $fieldObservation->observation->update(['identifier' => $user->full_name]);
                });
            });
    }
}
