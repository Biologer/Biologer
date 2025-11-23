<?php

namespace Tests;

use App\Models\FieldObservation;
use App\Models\Observation;
use Illuminate\Database\Eloquent\Collection;

class ObservationFactory
{
    public static function createManyFieldObservations($number = 1, $observation = [], $field = [])
    {
        return Collection::range(1, $number)->map(function () use ($observation, $field) {
            return static::createFieldObservation($observation, $field);
        });
    }

    public static function createManyUnnapprovedFieldObservations($number = 1, $observation = [], $field = [])
    {
        return Collection::range(1, $number)->map(function () use ($observation, $field) {
            return static::createUnapprovedFieldObservation($observation, $field);
        });
    }

    public static function createFieldObservation($observation = [], $field = [])
    {
        $fieldObservation = FieldObservation::factory()->create($field);
        $observation = Observation::factory()->make($observation);

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
