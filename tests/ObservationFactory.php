<?php

namespace Tests;

use App\Observation;
use App\FieldObservation;
use Illuminate\Database\Eloquent\Collection;

class ObservationFactory
{
    public static function createManyFieldObservations($number = 1, $observation = [], $field = [])
    {
        return (new Collection(range(1, $number)))->map(function () use ($observation, $field) {
            return static::createFieldObservation($observation, $field);
        });
    }

    public static function createManyUnnapprovedFieldObservations($number = 1, $observation = [], $field = [])
    {
        return (new Collection(range(1, $number)))->map(function () use ($observation, $field) {
            return static::createUnapprovedFieldObservation($observation, $field);
        });
    }

    public static function createFieldObservation($observation = [], $field = [])
    {
        $fieldObservation = factory(FieldObservation::class)->create($field);
        $observation = factory(Observation::class)->make($observation);

        $fieldObservation->observation()->save($observation);

        return $fieldObservation->setRelation('observation', $observation);
    }

    public static function createUnapprovedFieldObservation($observation = [], $field = [])
    {
        return static::createFieldObservation(array_merge($observation, [
            'approved_at' => null,
        ]), $field);
    }
}
