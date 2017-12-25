<?php

use Faker\Generator as Faker;

$factory->define(App\VerificationToken::class, function (Faker $faker) {
    static $password;

    return [
        'token' => str_random(10),
        'user_id' => factory(App\User::class),
    ];
});
