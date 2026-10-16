<?php

namespace Database\Factories;

use App\SpecimenCollection;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpecimenCollectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SpecimenCollection::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'code' => $this->faker->word(),
            'institution_name' => $this->faker->word(),
            'institution_code' => $this->faker->word(),
        ];
    }
}
