<?php

namespace Database\Factories;

use App\FieldObservation;
use App\License;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldObservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FieldObservation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'license' => License::firstId(),
            'taxon_suggestion' => 'Cerambyx cerdo',
            'found_dead' => $this->faker->boolean(),
            'found_dead_note' => $this->faker->sentence(),
            'time' => '11:00',
        ];
    }
}
