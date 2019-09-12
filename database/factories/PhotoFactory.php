<?php

use App\Photo;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Http\Testing\File;

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
