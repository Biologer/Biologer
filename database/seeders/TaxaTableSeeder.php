<?php

namespace Database\Seeders;

use App\Taxon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TaxaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = __DIR__.'/data/'.Str::snake(config('biologer.territory')).'_taxa.sql';

        if (Taxon::count() === 0 && File::exists($file)) {
            $taxa = File::get($file);

            if (empty($taxa)) {
                return;
            }

            DB::unprepared($taxa);

            Taxon::rebuildAncestry();
        }
    }
}
