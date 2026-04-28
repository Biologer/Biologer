<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTimedCountObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timed_count_observations', function (Blueprint $table) {
            $table->text('geometry')->nullable()->after('route_length');
            $table->double('latitude')->nullable()->after('geometry');
            $table->double('longitude')->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timed_count_observations', function (Blueprint $table) {
            $table->dropColumn('geometry');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
