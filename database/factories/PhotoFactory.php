<?php

use App\Photo;
use Faker\Generator as Faker;

use Illuminate\Http\UploadedFile;

$factory->define(Photo::class, function (Faker $faker) {
    $path = UploadedFile::fake()->image(str_random().'.jpg')->store('/photos', [
        'disk' => 'public',
    ]);

    return [
        'path' => $path,
        'url' => 'storage/'.$path,
        'author' => $faker->name,
        'license' => 10,
    ];
});
