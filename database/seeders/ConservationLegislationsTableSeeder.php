<?php

namespace Database\Seeders;

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
            'bs' => ['name' => 'DS, Aneks 2', 'description' => 'Direktiva o staništima, Aneks 2'],
            'en' => ['name' => 'Habitat, Annex 2', 'description' => 'Habitat Directive, Annex 2'],
            'hr' => ['name' => 'DS, Aneks 2', 'description' => 'Direktiva o staništima, Aneks 2'],
            'sr' => ['name' => 'ДС, Анекс 2', 'description' => 'Директива о стаништима, Aнекс 2'],
            'sr-Latn' => ['name' => 'DS, Aneks 2', 'description' => 'Direktiva o staništima, Aneks 2'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'habitat-4'])->update([
            'bs' => ['name' => 'DS, Aneks 4', 'description' => 'Direktiva o staništima, Aneks 4'],
            'en' => ['name' => 'Habitat, Annex 4', 'description' => 'Habitat Directive, Annex 4'],
            'hr' => ['name' => 'DS, Aneks 4', 'description' => 'Direktiva o staništima, Aneks 4'],
            'sr' => ['name' => 'ДС, Анекс 4', 'description' => 'Директива о стаништима, Анекс 4'],
            'sr-Latn' => ['name' => 'DS, Aneks 4', 'description' => 'Direktiva o staništima, Aneks 4'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'habitat-5'])->update([
            'bs' => ['name' => 'DS, Aneks 5', 'description' => 'Direktiva o staništima, Aneks 5'],
            'en' => ['name' => 'Habitat, Annex 5', 'description' => 'Habitat Directive, Annex 5'],
            'hr' => ['name' => 'DS, Aneks 5', 'description' => 'Direktiva o staništima, Aneks 5'],
            'sr' => ['name' => 'ДС, Анекс 5', 'description' => 'Директива о стаништима, Анекс 5'],
            'sr-Latn' => ['name' => 'DS, Aneks 5', 'description' => 'Direktiva o staništima, Aneks 5'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bern-1'])->update([
            'bs' => ['name' => 'Bern, Aneks 1', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Strogo zaštićene biljne vrste'],
            'en' => ['name' => 'Bern, Annex 1', 'description' => 'Convention on the Conservation of European Wildlife and Natural Habitats - Strictly protected flora species'],
            'hr' => ['name' => 'Bern, Aneks 1', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Strogo zaštićene biljne vrste'],
            'sr' => ['name' => 'Берн, Aнекс 1', 'description' => 'Конвенција о очувању европске дивље флоре, фауне и природних станишта - Строго заштићене биљне врсте'],
            'sr-Latn' => ['name' => 'Bern, Aneks 1', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Strogo zaštićene biljne vrste'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bern-2'])->update([
            'bs' => ['name' => 'Bern, Aneks 2', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Strogo zaštićene životinjske vrste'],
            'en' => ['name' => 'Bern, Annex 2', 'description' => 'Convention on the Conservation of European Wildlife and Natural Habitats - Strictly protected fauna species'],
            'hr' => ['name' => 'Bern, Aneks 2', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Strogo zaštićene životinjske vrste'],
            'sr' => ['name' => 'Берн, Aнекс 2', 'description' => 'Конвенција о очувању европске дивље флоре, фауне и природних станишта - Строго заштићене животињске врсте'],
            'sr-Latn' => ['name' => 'Bern, Aneks 2', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Strogo zaštićene životinjske vrste'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bern-3'])->update([
            'bs' => ['name' => 'Bern, Aneks 3', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Zaštićene životinjske vrste'],
            'en' => ['name' => 'Bern, Annex 3', 'description' => 'Convention on the Conservation of European Wildlife and Natural Habitats - Protected fauna species'],
            'hr' => ['name' => 'Bern, Aneks 3', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Zaštićene životinjske vrste'],
            'sr' => ['name' => 'Берн, Aнекс 3', 'description' => 'Конвенција о очувању европске дивље флоре, фауне и природних станишта - Заштићене животињске врсте'],
            'sr-Latn' => ['name' => 'Bern, Aneks 3', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Zaštićene životinjske vrste'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bern-resolution-6-1'])->update([
            'bs' => ['name' => 'Bern rezolucija 6', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Vrste koje zahtevaju zaštitu specifičnih staništa'],
            'en' => ['name' => 'Bern Resolution 6', 'description' => 'Convention on the Conservation of European Wildlife and Natural Habitats - Species requiring specific habitat conservation measures'],
            'hr' => ['name' => 'Bern rezolucija 6', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Vrste koje zahtevaju zaštitu specifičnih staništa'],
            'sr' => ['name' => 'Берн резолуција 6', 'description' => 'Конвенција о очувању европске дивље флоре, фауне и природних станишта - Врсте које захтевају заштиту специфичних станишта'],
            'sr-Latn' => ['name' => 'Bern rezolucija 6', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa - Vrste koje zahtevaju zaštitu specifičnih staništa'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'cites-1'])->update([
            'bs' => ['name' => 'CITES, Aneks 1', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja - Aneks 1, vrste koje možda nisu ugrožene ali to mogu postati'],
            'en' => ['name' => 'CITES, Appendix 1', 'description' => 'Convention on International Trade in Endangered Species of Wild Fauna and Flora - Appendix 1, species that are not necessarily now threatened with extinction but that may become so'],
            'hr' => ['name' => 'CITES, Aneks 1', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja - Aneks 1, vrste koje možda nisu ugrožene ali to mogu postati'],
            'sr' => ['name' => 'ЦИТЕС, Aнекс 1', 'description' => 'Конвенција о међународној трговини угроженим дивљим врстама биљака и животиња - Анекс 1, врсте које можда нису угрожене али то могу постати'],
            'sr-Latn' => ['name' => 'CITES, Aneks 1', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja - Aneks 1, vrste koje možda nisu ugrožene ali to mogu postati'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'cites-2'])->update([
            'bs' => ['name' => 'CITES, Aneks 2', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja - Aneks 2, vrste koje su najugroženije'],
            'en' => ['name' => 'CITES, Appendix 2', 'description' => 'Convention on International Trade in Endangered Species of Wild Fauna and Flora - Appendix 2, species that are the most endangered'],
            'hr' => ['name' => 'CITES, Aneks 2', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja - Aneks 2, vrste koje su najugroženije'],
            'sr' => ['name' => 'ЦИТЕС, Aнекс 2', 'description' => 'Конвенција о међународној трговини угроженим дивљим врстама биљака и животиња - Анекс 2, врсте које су најугроженије'],
            'sr-Latn' => ['name' => 'CITES, Aneks 2', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja - Aneks 2, vrste koje su najugroženije'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'birds-1'])->update([
            'bs' => ['name' => 'Direktiva o pticama, Aneks 1', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica - Aneks 1'],
            'en' => ['name' => 'Birds Directive, Appendix 1', 'description' => 'Directive 2009/147/EC of the European Parliament and of the Council of 30 November 2009 on the conservation of wild birds - Appendix 1'],
            'hr' => ['name' => 'Direktiva o pticama, Aneks 1', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica - Aneks 1'],
            'sr' => ['name' => 'Директива о птицама, Анекс 1', 'description' => 'Директива 2009/147/EC Европског парламента и Савета Европе од 30. новембра 2009. о очувању дивљих птица - Анекс 1'],
            'sr-Latn' => ['name' => 'Direktiva o pticama, Aneks 1', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica- Aneks 1'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'birds-2a'])->update([
            'bs' => ['name' => 'Direktiva o pticama, Aneks 2a', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica - Aneks 2a'],
            'en' => ['name' => 'Birds Directive, Appendix 2a', 'description' => 'Directive 2009/147/EC of the European Parliament and of the Council of 30 November 2009 on the conservation of wild birds - Appendix 2a'],
            'hr' => ['name' => 'Direktiva o pticama, Aneks 2a', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica - Aneks 2a'],
            'sr' => ['name' => 'Директива о птицама, Анекс 2а', 'description' => 'Директива 2009/147/EC Европског парламента и Савета Европе од 30. новембра 2009. о очувању дивљих птица - Анекс 2a'],
            'sr-Latn' => ['name' => 'Direktiva o pticama, Aneks 2a', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica- Aneks 2a'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'birds-2b'])->update([
            'bs' => ['name' => 'Direktiva o pticama, Aneks 2b', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica - Aneks 2b'],
            'en' => ['name' => 'Birds Directive, Appendix 2b', 'description' => 'Directive 2009/147/EC of the European Parliament and of the Council of 30 November 2009 on the conservation of wild birds - Appendix 2b'],
            'hr' => ['name' => 'Direktiva o pticama, Aneks 2b', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica - Aneks 2b'],
            'sr' => ['name' => 'Директива о птицама, Анекс 2б', 'description' => 'Директива 2009/147/EC Европског парламента и Савета Европе од 30. новембра 2009. о очувању дивљих птица - Анекс 2б'],
            'sr-Latn' => ['name' => 'Direktiva o pticama, Aneks 2b', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica- Aneks 2b'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'birds-3a'])->update([
            'bs' => ['name' => 'Direktiva o pticama, Aneks 3a', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica - Aneks 3a'],
            'en' => ['name' => 'Birds Directive, Appendix 3a', 'description' => 'Directive 2009/147/EC of the European Parliament and of the Council of 30 November 2009 on the conservation of wild birds - Appendix 3a'],
            'hr' => ['name' => 'Direktiva o pticama, Aneks 3a', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica - Aneks 3a'],
            'sr' => ['name' => 'Директива о птицама, Анекс 3а', 'description' => 'Директива 2009/147/EC Европског парламента и Савета Европе од 30. новембра 2009. о очувању дивљих птица - Анекс 3a'],
            'sr-Latn' => ['name' => 'Direktiva o pticama, Aneks 3a', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica- Aneks 3a'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'birds-3b'])->update([
            'bs' => ['name' => 'Direktiva o pticama, Aneks 3b', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica - Aneks 3b'],
            'en' => ['name' => 'Birds Directive, Appendix 3b', 'description' => 'Directive 2009/147/EC of the European Parliament and of the Council of 30 November 2009 on the conservation of wild birds - Appendix 3b'],
            'hr' => ['name' => 'Direktiva o pticama, Aneks 3b', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica - Aneks 3b'],
            'sr' => ['name' => 'Директива о птицама, Анекс 3б', 'description' => 'Директива 2009/147/EC Европског парламента и Савета Европе од 30. новембра 2009. о очувању дивљих птица - Анекс 3б'],
            'sr-Latn' => ['name' => 'Direktiva o pticama, Aneks 3b', 'description' => 'Direktiva 2009/147/EC Evropskog parlamenta i Saveta Evrope od 30. novembra 2009. o očuvanju divljih ptica- Aneks 3b'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bonn-1'])->update([
            'bs' => ['name' => 'CMS, Aneks 1', 'description' => 'Konvencija o zaštiti migratornih vrsta divljih životinja ili Bonska konvencija - Aneks 1'],
            'en' => ['name' => 'CMS, Appendix 1', 'description' => 'Convention on the Conservation of Migratory Species of Wild Animals (Bonn Convention) - Appendix 1'],
            'hr' => ['name' => 'CMS, Aneks 1', 'description' => 'Konvencija o zaštiti migratornih vrsta divljih životinja ili Bonska konvencija - Aneks 1'],
            'sr' => ['name' => 'ЦМС, Анекс 1', 'description' => 'Конвенција о очувању миграторних врста дивљих животиња ili Бонска конвенција - Анекс 1'],
            'sr-Latn' => ['name' => 'CMS, Aneks 1', 'description' => 'Konvenciјa o očuvanju migratornih vrsta divljih životinja ili Bonska konvenciјa - Aneks 1'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'bonn-2'])->update([
            'bs' => ['name' => 'CMS, Aneks 2', 'description' => 'Konvencija o zaštiti migratornih vrsta divljih životinja ili Bonska konvencija - Aneks 2'],
            'en' => ['name' => 'CMS, Appendix 2', 'description' => 'Convention on the Conservation of Migratory Species of Wild Animals (Bonn Convention) - Appendix 2'],
            'hr' => ['name' => 'CMS, Aneks 2', 'description' => 'Konvencija o zaštiti migratornih vrsta divljih životinja ili Bonska konvencija - Aneks 2'],
            'sr' => ['name' => 'ЦМС, Анекс 2', 'description' => 'Конвенција о очувању миграторних врста дивљих животиња ili Бонска конвенција - Анекс 2'],
            'sr-Latn' => ['name' => 'CMS, Aneks 2', 'description' => 'Konvenciјa o očuvanju migratornih vrsta divljih životinja ili Bonska konvenciјa - Aneks 2'],
        ]);
    }
}
