<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\RedList;
use Faker\Generator as Faker;

$factory->define(RedList::class, function (Faker $faker) {
    return [
        'slug' => $faker->unique()->word,
    ];
});
