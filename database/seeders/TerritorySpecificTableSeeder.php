<?php

namespace Database\Seeders;

use App\ConservationLegislation;
use App\RedList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TerritorySpecificTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $method = 'runFor'.Str::studly(config('biologer.territory'));

        if (method_exists($this, $method)) {
            $this->{$method}();
        }
    }

    public function runForSerbia()
    {
        ConservationLegislation::firstOrCreate(['slug' => 'serbia-1'])->update([
            'bs' => ['name' => 'Strogo zaštićena (RS)', 'description' => 'Prilog 1 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
            'en' => ['name' => 'Serbia 1', 'description' => 'Code of regulations on declaration and protection of strictly protected and protected wild species of plants, animals and fungi'],
            'hr' => ['name' => 'Strogo zaštićena (RS)', 'description' => 'Prilog 1 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
            'sr' => ['name' => 'Строго заштићена (РС)', 'description' => 'Прилог 1 Правилника о проглашењу и заштити строго заштићених и заштићених дивљих врста биљака, животиња и глива ("Службени гласник РС", бр 5/2010 и 47/2011)'],
            'sr-Latn' => ['name' => 'Strogo zaštićena (RS)', 'description' => 'Prilog 1 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
            'sr-Latn-ME' => ['name' => 'Strogo zaštićena (RS)', 'description' => 'Prilog 1 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
        ]);

        ConservationLegislation::firstOrCreate(['slug' => 'serbia-2'])->update([
            'bs' => ['name' => 'Zaštićena (RS)', 'description' => 'Prilog 2 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
            'en' => ['name' => 'Serbia 2', 'description' => 'Code of regulations on declaration and protection of strictly protected and protected wild species of plants, animals and fungi'],
            'hr' => ['name' => 'Zaštićena (RS)', 'description' => 'Prilog 2 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
            'sr' => ['name' => 'Заштићена (РС)', 'description' => 'Прилог 2 Правилника о проглашењу и заштити строго заштићених и заштићених дивљих врста биљака, животиња и глива ("Службени гласник РС", бр 5/2010 и 47/2011)'],
            'sr-Latn' => ['name' => 'Zaštićena (RS)', 'description' => 'Prilog 2 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
            'sr-Latn-ME' => ['name' => 'Zaštićena (RS)', 'description' => 'Prilog 2 Pravilnika o proglašenju i zaštiti strogo zaštićenih i zaštićenih divljih vrsta biljaka, životinja i gliva ("Službeni glasnik RS", br 5/2010 i 47/2011)'],
        ]);

        RedList::firstOrCreate(['slug' => 'balkans'])->update([
            'bs' => ['name' => 'Balkan'],
            'en' => ['name' => 'Balkans'],
            'hr' => ['name' => 'Balkan'],
            'sr' => ['name' => 'Балкан'],
            'sr-Latn' => ['name' => 'Balkan'],
            'sr-Latn-ME' => ['name' => 'Balkan'],
        ]);

        RedList::firstOrCreate(['slug' => 'serbia'])->update([
            'bs' => ['name' => 'Srbija'],
            'en' => ['name' => 'Serbia'],
            'hr' => ['name' => 'Srbija'],
            'sr' => ['name' => 'Србија'],
            'sr-Latn' => ['name' => 'Srbija'],
            'sr-Latn-ME' => ['name' => 'Srbija'],
        ]);
    }

    public function runForCroatia()
    {
        ConservationLegislation::firstOrCreate(['slug' => 'croatia'])->update([
            'bs' => ['name' => 'Strogo zaštićena (HR)', 'description' => 'Prilog 1 Pravilnika strogo zaštićenim vrstama ("Narodne novine", br. 144/13 i 73/16)'],
            'en' => ['name' => 'Protected in Croatia', 'description' => 'Code of regulations on strictly protected and protected wild species of plants, animals and fungi'],
            'hr' => ['name' => 'Strogo zaštićena (HR)', 'description' => 'Prilog 1 Pravilnika o strogo zaštićenim vrstama ("Narodne novine", br. 144/13 i 73/16)'],
            'sr' => ['name' => 'Строго заштићена (Хрватска)', 'description' => 'Прилог 1 Правилника о строго заштићеним врстама ("Народне новине", бр. 144/13 i 73/16)'],
            'sr-Latn' => ['name' => 'Strogo zaštićena (HR)', 'description' => 'Prilog 1 Pravilnika strogo zaštićenim vrstama ("Narodne novine", br. 144/13 i 73/16)'],
            'sr-Latn-ME' => ['name' => 'Strogo zaštićena (HR)', 'description' => 'Prilog 1 Pravilnika strogo zaštićenim vrstama ("Narodne novine", br. 144/13 i 73/16)'],
        ]);

        RedList::firstOrCreate(['slug' => 'EU'])->update([
            'bs' => ['name' => 'Evropska unija'],
            'en' => ['name' => 'European Union'],
            'hr' => ['name' => 'Europska unija'],
            'sr' => ['name' => 'Европска унија'],
            'sr-Latn' => ['name' => 'Evropska unija'],
            'sr-Latn-ME' => ['name' => 'Evropska unija'],
        ]);

        RedList::firstOrCreate(['slug' => 'croatia'])->update([
            'bs' => ['name' => 'Hrvatska'],
            'en' => ['name' => 'Croatia'],
            'hr' => ['name' => 'Hrvatska'],
            'sr' => ['name' => 'Хрватска'],
            'sr-Latn' => ['name' => 'Hrvatska'],
            'sr-Latn-ME' => ['name' => 'Hrvatska'],
        ]);
    }

    public function runForBiH()
    {
        RedList::firstOrCreate(['slug' => 'bih'])->update([
            'bs' => ['name' => 'BiH'],
            'en' => ['name' => 'BiH'],
            'hr' => ['name' => 'BiH'],
            'sr' => ['name' => 'БиХ'],
            'sr-Latn' => ['name' => 'BiH'],
            'sr-Latn-ME' => ['name' => 'BiH'],
        ]);
    }
}
