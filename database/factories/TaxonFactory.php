<?php

use Faker\Generator as Faker;

$factory->define(App\Taxon::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'rank_level' => 10,
    ];
});

$factory->define(App\ConservationList::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
    ];
});

$factory->define(App\RedList::class, function (Faker $faker) {
    return [
        'name' => $faker->country,
    ];
});

$factory->define(App\Stage::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
