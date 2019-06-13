<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\ConservationLegislation;

$factory->define(ConservationLegislation::class, function (Faker $faker) {
    return [
        'slug' => $faker->unique()->word,
    ];
});
