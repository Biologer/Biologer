<?php

namespace Database\Factories;

use App\Observation;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

class ObservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Observation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        static $userId;

        return [
            'year' => date('Y'),
            'month' => date('m'),
            'day' => date('d'),
            'location' => $this->faker->city(),
            'latitude' => 44.44444,
            'longitude' => 21.11111,
            'mgrs10k' => '38QMJ43',
            'approved_at' => Date::yesterday(),
            'created_by_id' => function () use ($userId) {
                return $userId ?: $userId = User::factory()->create()->id;
            },
            'observer' => $this->faker->name(),
            'identifier' => $this->faker->name(),
        ];
    }

    /**
     * @return static
    */
    public function unapproved()
    {
        return $this->state([
            'approved_at' => null,
        ]);
    }
}
