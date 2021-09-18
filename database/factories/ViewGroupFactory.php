<?php

namespace Database\Factories;

use App\ViewGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViewGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ViewGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'parent_id' => null,
        ];
    }
}
