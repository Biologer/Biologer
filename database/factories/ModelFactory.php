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
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
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

$factory->define(App\FieldObservation::class, function (Faker\Generator $faker) {
    return [
        'source' => $faker->name,
    ];
});

$factory->define(App\Observation::class, function (Faker\Generator $faker) {
    static $userId;

    $field = factory(App\FieldObservation::class)->create();

    return [
        'year' => date('Y'),
        'month' => date('m'),
        'day' => date('d'),
        'location' => $faker->city,
        'latitude' => 21.11111,
        'longitude' => 44.44444,
        'mgrs10k' => '38QMJ43',
        'approved_at' => Carbon\Carbon::yesterday(),
        'created_by_id' => $userId ?: $userId = factory(App\User::class)->create()->id,
        'details_type' => get_class($field),
        'details_id' => $field->id,
    ];
});

$factory->state(App\Observation::class, 'unapproved', function (Faker\Generator $faker) {
    return [
        'approved_at' => null,
    ];
});
