<?php

use Faker\Generator as Faker;

$factory->define(App\ViewGroup::class, function (Faker $faker) {
    return [
        'parent_id' => null,
    ];
});
