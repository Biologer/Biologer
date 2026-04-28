<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFieldObservationsTableAddTransectVisitId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('field_observations', function (Blueprint $table) {
            $table->unsignedBigInteger('transect_visit_id')->nullable()->after('timed_count_id');
            $table->foreign('transect_visit_id')->references('id')->on('transect_visits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('field_observations', function (Blueprint $table) {
            $table->dropForeign('field_observations_transect_visit_id_foreign');
            $table->dropColumn('transect_visit_id');
        });
    }
}
