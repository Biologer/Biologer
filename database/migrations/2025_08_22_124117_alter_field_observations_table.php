<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFieldObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('field_observations', function (Blueprint $table) {
            $table->unsignedBigInteger('timed_count_id')->nullable()->after('identified_by_id');
            $table->foreign('timed_count_id')->references('id')->on('timed_count_observations');
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
            $table->dropForeign('field_observations_timed_count_id_foreign');
            $table->dropColumn('timed_count_id');
        });
    }
}
