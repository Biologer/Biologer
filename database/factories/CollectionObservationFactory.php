<?php

namespace Database\Factories;

use App\CollectionObservation;
use App\ObservationIdentificationValidity;
use App\SpecimenCollection;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionObservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CollectionObservation::class;

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
            'collection_id' => SpecimenCollection::factory(),
        ];
    }
}
