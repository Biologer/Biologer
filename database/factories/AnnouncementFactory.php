<?php

use App\Announcement;
use Faker\Generator as Faker;

$factory->define(Announcement::class, function (Faker $faker) {
    return [
        'creator_id' => factory(App\User::class),
        'creator_name' => $faker->name,
        'en' => [
            'title' => $faker->sentence,
            'message' => $faker->paragraph,
        ],
        'private' => $faker->boolean,
    ];
});
