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
        DB::unprepared(File::get(dirname(__DIR__).'/fixtures/taxa.sql'));

        Taxon::rebuildAncestry();
    }
}
