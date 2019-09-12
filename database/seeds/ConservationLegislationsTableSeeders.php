<?php

use App\ConservationLegislation;
use Illuminate\Database\Seeder;

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
            'en' => ['name' => 'Habitat, Annex 2', 'description' => 'Habitat Directive, Annex 2'],
            'hr' => ['name' => 'DS, Aneks 2', 'description' => 'Direktiva o staništima, Aneks 2'],
            'sr' => ['name' => 'ДС, Анекс 2', 'description' => 'Директива о стаништима, Aнекс 2'],
            'sr-Latn' => ['name' => 'DS, Aneks 2', 'description' => 'Direktiva o staništima, Aneks 2'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'habitat-4'])->update([
            'en' => ['name' => 'Habitat, Annex 4', 'description' => 'Habitat Directive, Annex 4'],
            'hr' => ['name' => 'DS, Aneks 4', 'description' => 'Direktiva o staništima, Aneks 4'],
            'sr' => ['name' => 'ДС, Анекс 4', 'description' => 'Директива о стаништима, Анекс 4'],
            'sr-Latn' => ['name' => 'DS, Aneks 4', 'description' => 'Direktiva o staništima, Aneks 4'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'habitat-5'])->update([
            'en' => ['name' => 'Habitat, Annex 5', 'description' => 'Habitat Directive, Annex 5'],
            'hr' => ['name' => 'DS, Aneks 5', 'description' => 'Direktiva o staništima, Aneks 5'],
            'sr' => ['name' => 'ДС, Анекс 5', 'description' => 'Директива о стаништима, Анекс 5'],
            'sr-Latn' => ['name' => 'DS, Aneks 5', 'description' => 'Direktiva o staništima, Aneks 5'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bern-1'])->update([
            'en' => ['name' => 'Bern, Annex 1', 'description' => 'Convention on the Conservation of European Wildlife and Natural Habitats - Strictly protected flora species'],
            'hr' => ['name' => 'Bern, Aneks 1', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Strogo zaštićene biljne vrste'],
            'sr' => ['name' => 'Берн, Aнекс 1', 'description' => 'Конвенција о очувању европске дивље флоре, фауне и природних станишта - Строго заштићене биљне врсте'],
            'sr-Latn' => ['name' => 'Bern, Aneks 1', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Strogo zaštićene biljne vrste'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bern-2'])->update([
            'en' => ['name' => 'Bern, Annex 2', 'description' => 'Convention on the Conservation of European Wildlife and Natural Habitats - Strictly protected fauna species'],
            'hr' => ['name' => 'Bern, Aneks 2', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Strogo zaštićene životinjske vrste'],
            'sr' => ['name' => 'Берн, Aнекс 2', 'description' => 'Конвенција о очувању европске дивље флоре, фауне и природних станишта - Строго заштићене животињске врсте'],
            'sr-Latn' => ['name' => 'Bern, Aneks 2', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Strogo zaštićene životinjske vrste'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bern-3'])->update([
            'en' => ['name' => 'Bern, Annex 3', 'description' => 'Convention on the Conservation of European Wildlife and Natural Habitats - Protected fauna species'],
            'hr' => ['name' => 'Bern, Aneks 3', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Zaštićene životinjske vrste'],
            'sr' => ['name' => 'Берн, Aнекс 3', 'description' => 'Конвенција о очувању европске дивље флоре, фауне и природних станишта - Заштићене животињске врсте'],
            'sr-Latn' => ['name' => 'Bern, Aneks 3', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Zaštićene životinjske vrste'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bern-resolution-6-1'])->update([
            'en' => ['name' => 'Bern Resolution 6', 'description' => 'Convention on the Conservation of European Wildlife and Natural Habitats - Species requiring specific habitat conservation measures'],
            'hr' => ['name' => 'Bern rezolucija 6', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Vrste koje zahtevaju zaštitu specifičnih staništa'],
            'sr' => ['name' => 'Берн резолуција 6', 'description' => 'Конвенција о очувању европске дивље флоре, фауне и природних станишта - Врсте које захтевају заштиту специфичних станишта'],
            'sr-Latn' => ['name' => 'Bern rezolucija 6', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Vrste koje zahtevaju zaštitu specifičnih staništa'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'cites-1'])->update([
            'en' => ['name' => 'CITES, Appendix 1', 'description' => 'Convention on International Trade in Endangered Species of Wild Fauna and Flora - Appendix 1, species that are not necessarily now threatened with extinction but that may become so'],
            'hr' => ['name' => 'CITES, Aneks 1', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja - Aneks 1, vrste koje možda nisu ugrožene ali to mogu postati'],
            'sr' => ['name' => 'ЦИТЕС, Aнекс 1', 'description' => 'Конвенција о међународној трговини угроженим дивљим врстама биљака и животиња - Анекс 1, врсте које можда нису угрожене али то могу постати'],
            'sr-Latn' => ['name' => 'CITES, Aneks 1', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja - Aneks 1, vrste koje možda nisu ugrožene ali to mogu postati'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'cites-2'])->update([
            'en' => ['name' => 'CITES, Appendix 2', 'description' => 'Convention on International Trade in Endangered Species of Wild Fauna and Flora - Appendix 2, species that are the most endangered'],
            'hr' => ['name' => 'CITES, Aneks 2', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja - Aneks 2, vrste koje su najugroženije'],
            'sr' => ['name' => 'ЦИТЕС, Aнекс 2', 'description' => 'Конвенција о међународној трговини угроженим дивљим врстама биљака и животиња - Анекс 2, врсте које су најугроженије'],
            'sr-Latn' => ['name' => 'CITES, Aneks 2', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja - Aneks 2, vrste koje su najugroženije'],
        ]);
    }
}
