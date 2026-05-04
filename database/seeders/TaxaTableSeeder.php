<?php

namespace Database\Seeders;

use App\Taxon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TaxaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = __DIR__.'/data/base_taxa.sql';

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
