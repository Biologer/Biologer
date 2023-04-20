<?php

namespace Database\Seeders;

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
            'bs' => ['name' => 'Opaženo'],
            'en' => ['name' => 'Observed'],
            'hr' => ['name' => 'Opaženo'],
            'sr' => ['name' => 'Посматрано'],
            'sr-Latn' => ['name' => 'Posmatrano'],
            'sr-Latn-ME' => ['name' => 'Posmatrano'],
        ]);

        ObservationType::firstOrCreate(['slug' => 'photographed'])->update([
            'bs' => ['name' => 'Fotografisano'],
            'en' => ['name' => 'Photographed'],
            'hr' => ['name' => 'Fotografirano'],
            'sr' => ['name' => 'Фотографисано'],
            'sr-Latn' => ['name' => 'Fotografisano'],
            'sr-Latn-ME' => ['name' => 'Fotografisano'],
        ]);

        ObservationType::firstOrCreate(['slug' => 'call'])->update([
            'bs' => ['name' => 'Oglašavanje'],
            'en' => ['name' => 'Call'],
            'hr' => ['name' => 'Glasanje'],
            'sr' => ['name' => 'Оглашавање'],
            'sr-Latn' => ['name' => 'Oglašavanje'],
            'sr-Latn-ME' => ['name' => 'Oglašavanje'],
        ]);

        ObservationType::firstOrCreate(['slug' => 'exuviae'])->update([
            'bs' => ['name' => 'Svlak'],
            'en' => ['name' => 'Exuviae'],
            'hr' => ['name' => 'Svlak'],
            'sr' => ['name' => 'Егзувија'],
            'sr-Latn' => ['name' => 'Egzuvija'],
            'sr-Latn-ME' => ['name' => 'Egzuvija'],
        ]);
    }
}
