<?php

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
        RedList::where(['slug' => 'global'])->firstOr(function () {
            RedList::create([
                'slug' => 'global',
                'en' => ['name' => 'Global'],
                'sr-Latn' => ['name' => 'Globalna'],
                'sr' => ['name' => 'Глобална'],
            ]);
        });

        RedList::where(['slug' => 'europe'])->firstOr(function () {
            RedList::create([
                'slug' => 'europe',
                'en' => ['name' => 'Europe'],
                'sr-Latn' => ['name' => 'Evropa'],
                'sr' => ['name' => 'Европа'],
            ]);
        });

        RedList::where(['slug' => 'serbia'])->firstOr(function () {
            RedList::create([
                'slug' => 'serbia',
                'en' => ['name' => 'Serbia'],
                'sr-Latn' => ['name' => 'Srbija'],
                'sr' => ['name' => 'Србија'],
            ]);
        });
    }
}
