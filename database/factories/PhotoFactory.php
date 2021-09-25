<?php

namespace Database\Factories;

use App\ImageLicense;
use App\Photo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Str;

class PhotoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Photo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'path' => File::image(Str::random().'.jpg')->store('photos', [
                'disk' => 'public',
            ]),
            'url' => function ($attributes) {
                return 'storage/'.$attributes['path'];
            },
            'author' => $this->faker->name(),
            'license' => ImageLicense::CC_BY_SA,
        ];
    }

    /**
     * @return static
     */
    public function public()
    {
        return $this->state([
            'license' => ImageLicense::CC_BY_SA,
        ]);
    }
}
