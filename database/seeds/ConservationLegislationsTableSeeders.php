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
        ConservationLegislation::where(['slug' => 'serbia-1'])->firstOr(function () {
            ConservationLegislation::create([
                'slug' => 'serbia-1',
                'en' => ['name' => 'Serbia 1', 'description' => 'Code of regulations on declaration and protection of strictly protected and protected wild species of plants, animals and fungi'],
                'sr-Latn' => ['name' => 'Zaštićena (RS)', 'description' => 'Prilog 1 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
                'sr' => ['name' => 'Заштићена (РС)', 'description' => 'Прилог 2 Правилника о проглашењу и заштити строго заштићених и заштићених дивљих врста биљака, животиња и глива ("Службени гласник РС", бр 5/2010 и 47/2011)'],
            ]);
        });

        ConservationLegislation::where(['slug' => 'serbia-2'])->firstOr(function () {
            ConservationLegislation::create([
                'slug' => 'serbia-2',
                'en' => ['name' => 'Serbia 2', 'description' => 'Code of regulations on declaration and protection of strictly protected and protected wild species of plants, animals and fungi'],
                'sr-Latn' => ['name' => 'Strogo zaštićena (RS)', 'description' => 'Prilog 2 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
                'sr' => ['name' => 'Строго заштићена (РС)', 'description' => 'Прилог 2 Правилника о проглашењу и заштити строго заштићених и заштићених дивљих врста биљака, животиња и глива ("Службени гласник РС", бр 5/2010 и 47/2011)'],
            ]);
        });

        ConservationLegislation::where(['slug' => 'habitat-2'])->firstOr(function () {
            ConservationLegislation::create([
                'slug' => 'habitat-2',
                'en' => ['name' => 'Habitat 2', 'description' => 'Habitat Directive, Anex 2'],
                'sr-Latn' => ['name' => 'DS, Aneks 2', 'description' => 'Direktiva o staništima, Anex 2'],
                'sr' => ['name' => 'ДС, Анекс 2', 'description' => 'Директива о стаништима, Anex 2'],
            ]);
        });

        ConservationLegislation::where(['slug' => 'habitat-4'])->firstOr(function () {
            ConservationLegislation::create([
                'slug' => 'habitat-4',
                'en' => ['name' => 'Habitat 4', 'description' => 'Habitat Directive, Anex 2'],
                'sr-Latn' => ['name' => 'DS, Aneks 4', 'description' => 'Direktiva o staništima, Aneks 2'],
                'sr' => ['name' => 'ДС, Анекс 4', 'description' => 'Директива о стаништима, Aneкс 2'],
            ]);
        });

        ConservationLegislation::where(['slug' => 'bern'])->firstOr(function () {
            ConservationLegislation::create([
                'slug' => 'bern',
                'en' => ['name' => 'Bern', 'description' => 'Convention on the Conservation of European Wildlife and Natural Habitats'],
                'sr-Latn' => ['name' => 'Bern', 'description' => 'Konvencija o očuvanju evropske divlje flore, faune i prirodnih staništa'],
                'sr' => ['name' => 'Берн', 'description' => 'Конвенција о очувању европске дивље флоре, фауне и природних станишта'],
            ]);
        });

        ConservationLegislation::where(['slug' => 'cites'])->firstOr(function () {
            ConservationLegislation::create([
                'slug' => 'cites',
                'en' => ['name' => 'CITES', 'description' => 'Convention on International Trade in Endangered Species of Wild Fauna and Flora'],
                'sr-Latn' => ['name' => 'CITES', 'description' => 'Konvencija o međunarodnoj trgovini ugroženim divljim vrstama biljaka i životinja'],
                'sr' => ['name' => 'ЦИТЕС', 'description' => 'Конвенција о међународној трговини угроженим дивљим врстама биљака и животиња'],
            ]);
        });
    }
}
