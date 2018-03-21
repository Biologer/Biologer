<?php

use App\Photo;
use Faker\Generator as Faker;
use Illuminate\Http\Testing\File;

$factory->define(Photo::class, function (Faker $faker) {
    $path = File::image(str_random().'.jpg')->store('photos', [
        'disk' => 'public',
    ]);

    return [
        'path' => $path,
        'url' => 'storage/'.$path,
        'author' => $faker->name,
        'license' => 10,
    ];
});
