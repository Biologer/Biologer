<?php

namespace Database\Factories;

use App\Models\ConservationLegislation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConservationLegislationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConservationLegislation::class;

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
