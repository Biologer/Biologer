<?php

use Faker\Generator as Faker;

$factory->define(App\Taxon::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'rank' => 'species',
    ];
});

$factory->define(App\ConservationList::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->city,
    ];
});

$factory->define(App\RedList::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->country,
    ];
});

$factory->define(App\Stage::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});
