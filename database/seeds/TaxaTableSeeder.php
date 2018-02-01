<?php

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
        DB::unprepared(File::get(__DIR__.'/data/taxa.sql'));

        Taxon::rebuildAncestry();
    }
}
