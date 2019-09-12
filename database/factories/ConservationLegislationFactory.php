<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\ConservationLegislation;
use Faker\Generator as Faker;

$factory->define(ConservationLegislation::class, function (Faker $faker) {
    return [
        'slug' => $faker->unique()->word,
    ];
});
