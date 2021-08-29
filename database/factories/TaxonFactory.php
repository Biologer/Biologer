<?php

namespace Database\Factories;

use App\Taxon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Taxon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->name(),
            'rank' => 'species',
            'allochthonous' => false,
            'invasive' => false,
            'restricted' => false,
        ];
    }
}
