<?php

namespace Database\Factories;

use App\RedList;
use Illuminate\Database\Eloquent\Factories\Factory;

class RedListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RedList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->unique()->country(),
        ];
    }
}
