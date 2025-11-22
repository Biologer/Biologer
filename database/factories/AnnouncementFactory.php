<?php

namespace Database\Factories;

use App\Announcement;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition()
    {
        return [
            'creator_id' => User::factory(),
            'creator_name' => $this->faker->name(),
            'private' => $this->faker->boolean(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Announcement $announcement) {
            $announcement->translateOrNew('en')->title = $this->faker->sentence();
            $announcement->translateOrNew('en')->message = $this->faker->paragraph();
        })->afterCreating(function (Announcement $announcement) {
            $announcement->save();
        });
    }
}
