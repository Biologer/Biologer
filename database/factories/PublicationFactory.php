<?php

use App\User;
use App\Publication;
use App\PublicationType;
use Faker\Generator as Faker;

$factory->define(Publication::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(PublicationType::toArray()),
        'year' => $faker->randomNumber(4),
        'authors' => collect([
            'John Doe',
        ]),
        'editors' => collect([
            'Jane Doe',
        ]),
        'title' => $faker->sentence(3),
        'name' => $faker->sentence(2),
        'issue' => $faker->randomDigitNotNull,
        'place' => $faker->city,
        'publisher' => $faker->words(2, true),
        'page_count' => $faker->randomDigitNotNull,
        'page_range' => 'p10-12',
        'created_by_id' => factory(User::class),
    ];
});
