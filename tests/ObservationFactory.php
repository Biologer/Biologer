<?php

namespace Tests;

use App\Observation;
use App\FieldObservation;
use Illuminate\Database\Eloquent\Collection;

class ObservationFactory
{
    public static function createFieldObservation($overrides = [], $number = 1)
    {
        if ($number > 1) {
            return (new Collection(range(1, $number)))
            ->map(function () use ($overrides) {
                return static::createFieldObservation($overrides);
            });
        }

        return static::createOneFieldObservation($overrides);
    }

    public static function createOneFieldObservation($overrides)
    {
        $fieldObservation = factory(FieldObservation::class)->create($overrides);
        $observation = factory(Observation::class)->make($overrides);

        $fieldObservation->observation()->save($observation);

        return $fieldObservation->setRelation('observation', $observation);
    }

    public function createUnapprovedFieldObseration($overrides)
    {
        return static::createFieldObservation(array_merge($overrides, [
            'approved_at' => null,
        ]));
    }
}
