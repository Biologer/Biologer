<?php

use App\Photo;
use Faker\Generator as Faker;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Str;

$factory->define(Photo::class, function (Faker $faker) {
    $path = File::image(Str::random().'.jpg')->store('photos', [
        'disk' => 'public',
    ]);

    return [
        'path' => $path,
        'url' => 'storage/'.$path,
        'author' => $faker->name,
        'license' => 10,
    ];
});
