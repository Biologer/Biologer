<?php

namespace Database\Factories;

use App\LiteratureObservation;
use App\ObservationIdentificationValidity;
use App\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;

class LiteratureObservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LiteratureObservation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'original_date' => $this->faker->date('F j, Y'),
            'original_locality' => $this->faker->city(),
            'original_elevation' => '300-500m',
            'original_coordinates' => '21.123123123,43.12312312',
            'original_identification_validity' => $this->faker->randomElement(
                ObservationIdentificationValidity::toArray()
            ),
            'georeferenced_by' => $this->faker->name(),
            'georeferenced_date' => $this->faker->date('Y-m-d'),
            'minimum_elevation' => $this->faker->randomDigitNotNull(),
            'maximum_elevation' => $this->faker->randomDigitNotNull(),
            'publication_id' => Publication::factory(),
            'is_original_data' => true,
            'cited_publication_id' => null,
        ];
    }
}
