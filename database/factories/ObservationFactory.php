<?php

use App\CollectionObservation;
use App\FieldObservation;
use App\License;
use App\LiteratureObservation;
use App\Observation;
use App\ObservationIdentificationValidity;
use App\Publication;
use App\SpecimenCollection;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(FieldObservation::class, function (Faker $faker) {
    return [
        'license' => License::firstId(),
        'taxon_suggestion' => 'Cerambyx cerdo',
        'found_dead' => $faker->boolean,
        'found_dead_note' => $faker->sentence,
        'time' => '11:00',
    ];
});

$factory->define(Observation::class, function (Faker $faker) {
    static $userId;

    return [
        'year' => date('Y'),
        'month' => date('m'),
        'day' => date('d'),
        'location' => $faker->city,
        'latitude' => 44.44444,
        'longitude' => 21.11111,
        'mgrs10k' => '38QMJ43',
        'approved_at' => Carbon::yesterday(),
        'created_by_id' => function () use ($userId) {
            return $userId ?: $userId = factory(User::class)->create()->id;
        },
        'observer' => $faker->name,
        'identifier' => $faker->name,
    ];
});

$factory->state(Observation::class, 'unapproved', [
    'approved_at' => null,
]);

$factory->define(LiteratureObservation::class, function (Faker $faker) {
    return [
        'original_date' => $faker->date('F j, Y'),
        'original_locality' => $faker->city,
        'original_elevation' => '300-500m',
        'original_coordinates' => '21.123123123,43.12312312',
        'original_identification_validity' => $faker->randomElement(
            ObservationIdentificationValidity::toArray()
        ),
        'georeferenced_by' => $faker->name,
        'georeferenced_date' => $faker->date('Y-m-d'),
        'minimum_elevation' => $faker->randomDigitNotNull,
        'maximum_elevation' => $faker->randomDigitNotNull,
        'publication_id' => factory(Publication::class),
        'is_original_data' => true,
        'cited_publication_id' => null,
    ];
});

$factory->define(CollectionObservation::class, function (Faker $faker) {
    return [
        'original_date' => $faker->date('F j, Y'),
        'original_locality' => $faker->city,
        'original_elevation' => '300-500m',
        'original_coordinates' => '21.123123123,43.12312312',
        'original_identification_validity' => $faker->randomElement(
            ObservationIdentificationValidity::toArray()
        ),
        'georeferenced_by' => $faker->name,
        'georeferenced_date' => $faker->date('Y-m-d'),
        'minimum_elevation' => $faker->randomDigitNotNull,
        'maximum_elevation' => $faker->randomDigitNotNull,
        'collection_id' => factory(SpecimenCollection::class),
    ];
});
