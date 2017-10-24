<?php

use Faker\Generator as Faker;

$factory->define(App\Taxon::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'category_level' => 10,
    ];
});
