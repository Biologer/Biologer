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
        ObservationType::firstOrCreate(['slug' => 'observed'])->update([
            'en' => ['name' => 'Observed'],
            'hr' => ['name' => 'Posmatrano'],
            'sr' => ['name' => 'Посматрано'],
            'sr-Latn' => ['name' => 'Posmatrano'],
        ]);

        ObservationType::firstOrCreate(['slug' => 'photographed'])->update([
            'en' => ['name' => 'Photographed'],
            'hr' => ['name' => 'Fotografisano'],
            'sr' => ['name' => 'Фотографисано'],
            'sr-Latn' => ['name' => 'Fotografisano'],
        ]);
    }
}
