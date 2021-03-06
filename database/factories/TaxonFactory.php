<?php

use Faker\Generator as Faker;

$factory->define(App\Taxon::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'rank' => 'species',
        'allochthonous' => false,
        'invasive' => false,
        'restricted' => false,
    ];
});

$factory->define(App\ConservationLegislation::class, function (Faker $faker) {
    return [
        'slug' => $faker->unique()->city,
    ];
});

$factory->define(App\ConservationDocument::class, function (Faker $faker) {
    return [
        'slug' => $faker->unique()->city,
    ];
});

$factory->define(App\RedList::class, function (Faker $faker) {
    return [
        'slug' => $faker->unique()->country,
    ];
});

$factory->define(App\Stage::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});
