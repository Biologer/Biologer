<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLiteratureObservationsTableAddCollectionDateRangeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('literature_observations', function (Blueprint $table) {
            $table->unsignedSmallInteger('collecting_start_year')->nullable()->after('other_original_data');
            $table->unsignedTinyInteger('collecting_start_month')->nullable()->after('collecting_start_year');
            $table->unsignedSmallInteger('collecting_end_year')->nullable()->after('collecting_start_month');
            $table->unsignedTinyInteger('collecting_end_month')->nullable()->after('collecting_end_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('literature_observations', function (Blueprint $table) {
            $table->dropColumn('collecting_start_year');
            $table->dropColumn('collecting_start_month');
            $table->dropColumn('collecting_end_year');
            $table->dropColumn('collecting_end_month');
        });
    }
}
