<?php

namespace Database\Seeders;

use App\RedList;
use Illuminate\Database\Seeder;

class RedListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RedList::firstOrCreate(['slug' => 'global'])->update([
            'bs' => ['name' => 'Globalna'],
            'en' => ['name' => 'Global'],
            'hr' => ['name' => 'Globalna'],
            'sr' => ['name' => 'Глобална'],
            'sr-Latn' => ['name' => 'Globalna'],
            'sr-Latn-ME' => ['name' => 'Globalna'],
        ]);

        RedList::firstOrCreate(['slug' => 'europe'])->update([
            'bs' => ['name' => 'Evropa'],
            'en' => ['name' => 'Europe'],
            'hr' => ['name' => 'Europa'],
            'sr' => ['name' => 'Европа'],
            'sr-Latn' => ['name' => 'Evropa'],
            'sr-Latn-ME' => ['name' => 'Evropa'],
        ]);
    }
}
