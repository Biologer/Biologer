<?php

use Illuminate\Database\Seeder;
use App\ConservationLegislation;

class ConservationLegislationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConservationLegislation::firstOrCreate(['slug' => 'habitat-2'])->update([
            'en' => ['name' => 'Habitat 2', 'description' => 'Habitat Directive, Anex 2'],
            'hr' => ['name' => 'DS, Aneks 2', 'description' => 'Direktiva o staništima, Anex 2'],
            'sr' => ['name' => 'ДС, Анекс 2', 'description' => 'Директива о стаништима, Anex 2'],
            'sr-Latn' => ['name' => 'DS, Aneks 2', 'description' => 'Direktiva o staništima, Anex 2'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'habitat-4'])->update([
            'en' => ['name' => 'Habitat 4', 'description' => 'Habitat Directive, Anex 2'],
            'hr' => ['name' => 'DS, Aneks 4', 'description' => 'Direktiva o staništima, Aneks 2'],
            'sr' => ['name' => 'ДС, Анекс 4', 'description' => 'Директива о стаништима, Aneкс 2'],
            'sr-Latn' => ['name' => 'DS, Aneks 4', 'description' => 'Direktiva o staništima, Aneks 2'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bern'])->update([
            'en' => ['name' => 'Bern', 'description' => 'Convention on the Conservation of European Wildlife and Natural Habitats'],
            'hr' => ['name' => 'Bern', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa'],
            'sr' => ['name' => 'Берн', 'description' => 'Конвенција о очувању европске дивље флоре, фауне и природних станишта'],
            'sr-Latn' => ['name' => 'Bern', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'cites'])->update([
            'en' => ['name' => 'CITES', 'description' => 'Convention on International Trade in Endangered Species of Wild Fauna and Flora'],
            'hr' => ['name' => 'CITES', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja'],
            'sr' => ['name' => 'ЦИТЕС', 'description' => 'Конвенција о међународној трговини угроженим дивљим врстама биљака и животиња'],
            'sr-Latn' => ['name' => 'CITES', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja'],
        ]);
    }
}
