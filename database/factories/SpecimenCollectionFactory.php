<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SpecimenCollection;
use Faker\Generator as Faker;

$factory->define(SpecimenCollection::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'code' => $faker->word,
        'institution_name' => $faker->word,
        'institution_code' => $faker->word,
    ];
});
