<?php

use App\ObservationType;

use Illuminate\Database\Seeder;

class ObservationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ObservationType::where(['slug' => 'observed'])->firstOr(function () {
            ObservationType::create([
                'slug' => 'observed',
                'en' => ['name' => 'Observed'],
                'sr-Latn' => ['name' => 'Uočeno'],
                'sr' => ['name' => 'Уочено'],
            ]);
        });

        ObservationType::where(['slug' => 'photographed'])->firstOr(function () {
            ObservationType::create([
                'slug' => 'photographed',
                'en' => ['name' => 'Photographed'],
                'sr-Latn' => ['name' => 'Fotografisano'],
                'sr' => ['name' => 'Фотографисано'],
            ]);
        });
    }
}
