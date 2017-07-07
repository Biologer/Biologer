<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Taxon::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\Observation::class, function (Faker\Generator $faker) {
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    return [
        'observed_at' => $faker->dateTime,
        'location' => $faker->city,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'mgrs_field' => rand(1, 5).rand(1, 5).$letters[rand(0, count($letters)-1)].$letters[rand(0, count($letters)-1)],
        'approved_at' => Carbon\Carbon::yesterday()
    ];
});

$factory->state(App\Observation::class, 'unapproved', function (Faker\Generator $faker) {
    return [
        'approved_at' => null,
    ];
});
