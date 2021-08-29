<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MigrateDataAndPhotoLicenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Open with ID 11 (removed) -> Open
        DB::table('field_observations')->where('license', 11)->update(['license' => 10]);
        // Open with ID 11 (removed) -> Open
        DB::table('photos')->where('license', 11)->update(['license' => 10]);
        // Temporarily closed (removed) -> Closed
        DB::table('photos')->where('license', 35)->update(['license' => 40]);
    }
}
