<?php

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
        ConservationDocument::where(['slug' => 'pba'])->firstOr(function () {
            ConservationDocument::create([
                'slug' => 'pba',
                'en' => ['name' => 'PBA', 'description' => 'Prime Butterfly Areas'],
                'sr-Latn' => ['name' => 'PBA', 'description' => 'Odabrana područja za dnevne leptire'],
                'sr' => ['name' => 'ПБА', 'description' => 'Одабрана подручја за дневне лептире'],
            ]);
        });
    }
}
