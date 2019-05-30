<?php

use Faker\Generator as Faker;

$factory->define(App\FieldObservation::class, function (Faker $faker) {
    return [
        'license' => App\License::firstId(),
        'taxon_suggestion' => 'Cerambyx cerdo',
        'found_dead' => $faker->boolean,
        'found_dead_note' => $faker->sentence,
        'time' => '11:00',
    ];
});

$factory->define(App\Observation::class, function (Faker $faker) {
    static $userId;

    return [
        'year' => date('Y'),
        'month' => date('m'),
        'day' => date('d'),
        'location' => $faker->city,
        'latitude' => 44.44444,
        'longitude' => 21.11111,
        'mgrs10k' => '38QMJ43',
        'approved_at' => Carbon\Carbon::yesterday(),
        'created_by_id' => function () use ($userId) {
            return $userId ?: $userId = factory(App\User::class)->create()->id;
        },
        'observer' => $faker->name,
        'identifier' => $faker->name,
    ];
});

$factory->state(App\Observation::class, 'unapproved', function (Faker $faker) {
    return [
        'approved_at' => null,
    ];
});

$factory->define(App\LiteratureObservation::class, function (Faker $faker) {
    return [
        'original_date' => $faker->date('F j, Y'),
        'original_locality' => $faker->city,
        'original_elevation' => '300-500m',
        'original_coordinates' => '21.123123123,43.12312312',
        'original_identification_validity' => $faker->randomElement(
            App\LiteratureObservationIdentificationValidity::toArray()
        ),
        'georeferenced_by' => $faker->name,
        'georeferenced_date' => $faker->date('Y-m-d'),
        'minimum_elevation' => $faker->randomDigitNotNull,
        'maximum_elevation' => $faker->randomDigitNotNull,
        'publication_id' => factory(App\Publication::class),
        'is_original_data' => true,
        'cited_publication_id' => null,
    ];
});
