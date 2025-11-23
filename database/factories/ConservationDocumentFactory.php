<?php

namespace Database\Factories;

use App\Models\ConservationDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConservationDocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConservationDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->unique()->city(),
        ];
    }
}
