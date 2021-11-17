<?php

namespace Database\Seeders;

use App\ConservationDocument;
use Illuminate\Database\Seeder;

class ConservationDocumentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConservationDocument::firstOrCreate(['slug' => 'pba'])->update([
            'bs' => ['name' => 'PBA', 'description' => 'Odabrana područja za dnevne leptire'],
            'en' => ['name' => 'PBA', 'description' => 'Prime Butterfly Areas'],
            'hr' => ['name' => 'PBA', 'description' => 'Odabrana područja za dnevne leptire'],
            'sr' => ['name' => 'ПБА', 'description' => 'Одабрана подручја за дневне лептире'],
            'sr-Latn' => ['name' => 'PBA', 'description' => 'Odabrana područja za dnevne leptire'],
        ]);
    }
}
