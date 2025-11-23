<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => Str::random(10),
            'settings' => '[]',
            'email_verified_at' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return static
     */
    public function unverified()
    {
        return $this->state([
            'email_verified_at' => null,
        ]);
    }

    /**
     * @param  \App\Taxon|\Illuminate\Database\Eloquent\Collection|null
     * @return static
     */
    public function curator($taxa = null)
    {
        return $this->afterCreating(function ($user) use ($taxa) {
            $user->assignRoles('curator');

            $taxa && $user->curatedTaxa()->attach($taxa);
        });
    }
}
