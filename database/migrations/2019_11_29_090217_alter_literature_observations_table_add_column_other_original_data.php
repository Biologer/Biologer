<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLiteratureObservationsTableAddColumnOtherOriginalData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('literature_observations', function (Blueprint $table) {
            $table->text('other_original_data')->nullable()->after('original_identification_validity');
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
            $table->dropColumn('other_original_data');
        });
    }
}
