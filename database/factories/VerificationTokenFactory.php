<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\VerificationToken::class, function (Faker $faker) {
    static $password;

    return [
        'token' => Str::random(10),
        'user_id' => factory(App\User::class),
    ];
});
