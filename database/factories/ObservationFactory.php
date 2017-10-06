<?php

use Faker\Generator as Faker;

$factory->define(App\FieldObservation::class, function (Faker $faker) {
    return [
        'source' => $faker->name,
    ];
});

$factory->define(App\Observation::class, function (Faker $faker) {
    static $userId;

    $field = factory(App\FieldObservation::class)->create();

    return [
        'year' => date('Y'),
        'month' => date('m'),
        'day' => date('d'),
        'location' => $faker->city,
        'latitude' => 21.11111,
        'longitude' => 44.44444,
        'mgrs10k' => '38QMJ43',
        'approved_at' => Carbon\Carbon::yesterday(),
        'created_by_id' => function () use ($userId) {
            return $userId ?: $userId = factory(App\User::class)->create()->id;
        },
        'details_type' => get_class($field),
        'details_id' => $field->id,
    ];
});

$factory->state(App\Observation::class, 'unapproved', function (Faker $faker) {
    return [
        'approved_at' => null,
    ];
});
