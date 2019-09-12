<?php

use App\ConservationLegislation;
use App\RedList;
use Illuminate\Database\Seeder;

class TerritorySpecificTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $method = 'runFor'.studly_case(config('biologer.territory'));

        $this->{$method}();
    }

    public function runForSerbia()
    {
        ConservationLegislation::firstOrCreate(['slug' => 'serbia-1'])->update([
            'en' => ['name' => 'Serbia 1', 'description' => 'Code of regulations on declaration and protection of strictly protected and protected wild species of plants, animals and fungi'],
            'hr' => ['name' => 'Strogo zaštićena (RS)', 'description' => 'Prilog 1 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
            'sr' => ['name' => 'Строго заштићена (РС)', 'description' => 'Прилог 2 Правилника о проглашењу и заштити строго заштићених и заштићених дивљих врста биљака, животиња и глива ("Службени гласник РС", бр 5/2010 и 47/2011)'],
            'sr-Latn' => ['name' => 'Strogo zaštićena (RS)', 'description' => 'Prilog 1 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'serbia-2'])->update([
            'en' => ['name' => 'Serbia 2', 'description' => 'Code of regulations on declaration and protection of strictly protected and protected wild species of plants, animals and fungi'],
            'hr' => ['name' => 'Zaštićena (RS)', 'description' => 'Prilog 2 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
            'sr' => ['name' => 'Заштићена (РС)', 'description' => 'Прилог 2 Правилника о проглашењу и заштити строго заштићених и заштићених дивљих врста биљака, животиња и глива ("Службени гласник РС", бр 5/2010 и 47/2011)'],
            'sr-Latn' => ['name' => 'Zaštićena (RS)', 'description' => 'Prilog 2 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
        ]);

        RedList::firstOrCreate(['slug' => 'serbia'])->update([
            'en' => ['name' => 'Serbia'],
            'hr' => ['name' => 'Srbija'],
            'sr' => ['name' => 'Србија'],
            'sr-Latn' => ['name' => 'Srbija'],
        ]);
    }

    public function runForCroatia()
    {
        ConservationLegislation::firstOrCreate(['slug' => 'croatia'])->update([
            'en' => ['name' => 'Protected in Croatia', 'description' => 'Code of regulations on strictly protected and protected wild species of plants, animals and fungi'],
            'hr' => ['name' => 'Strogo zaštićena (HR)', 'description' => 'Prilog 1 Pravilnika o strogo zaštićenim vrstama ("Narodne novine", br. 144/13 i 73/16)'],
            'sr' => ['name' => 'Строго заштићена (Хрватска)', 'description' => 'Прилог 1 Правилника о строго заштићеним врстама ("Народне новине", бр. 144/13 i 73/16)'],
            'sr-Latn' => ['name' => 'Strogo zaštićena (HR)', 'description' => 'Prilog 1 Pravilnika strogo zaštićenim vrstama ("Narodne novine", br. 144/13 i 73/16)'],
        ]);

        RedList::firstOrCreate(['slug' => 'EU'])->update([
            'en' => ['name' => 'European Union'],
            'hr' => ['name' => 'Europska unija'],
            'sr' => ['name' => 'Европска унија'],
            'sr-Latn' => ['name' => 'Evropska unija'],
        ]);

        RedList::firstOrCreate(['slug' => 'croatia'])->update([
            'en' => ['name' => 'Croatia'],
            'hr' => ['name' => 'Hrvatska'],
            'sr' => ['name' => 'Хрватска'],
            'sr-Latn' => ['name' => 'Hrvatska'],
        ]);
    }
}
