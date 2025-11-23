<?php

namespace Database\Factories;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Announcement::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'creator_id' => User::factory(),
            'creator_name' => $this->faker->name(),
            'en' => [
                'title' => $this->faker->sentence(),
                'message' => $this->faker->paragraph(),
            ],
            'private' => $this->faker->boolean(),
        ];
    }
}
