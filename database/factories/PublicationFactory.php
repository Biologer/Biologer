<?php

namespace Database\Factories;

use App\Models\Publication;
use App\PublicationType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Publication::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(PublicationType::toArray()),
            'year' => $this->faker->randomNumber(4),
            'authors' => collect([
                ['first_name' => 'John', 'last_name' => 'Doe'],
            ]),
            'editors' => collect([
                ['first_name' => 'Jane', 'last_name' => 'Doe'],
            ]),
            'title' => $this->faker->sentence(3),
            'name' => $this->faker->sentence(2),
            'issue' => $this->faker->randomDigitNotNull(),
            'place' => $this->faker->city,
            'publisher' => $this->faker->words(2, true),
            'page_count' => $this->faker->randomDigitNotNull(),
            'page_range' => 'p10-12',
            'created_by_id' => User::factory(),
        ];
    }
}
