<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFieldObservationsTableAddObservedByIdAndIdentifiedById extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('field_observations', function (Blueprint $table) {
            $table->unsignedInteger('observed_by_id')->nullable()->after('time');
            $table->unsignedInteger('identified_by_id')->nullable()->after('observed_by_id');

            $table->foreign('observed_by_id')->references('id')->on('users');
            $table->foreign('identified_by_id')->references('id')->on('users');
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
            $table->dropForeign(['observed_by_id']);
            $table->dropForeign(['identified_by_id']);

            $table->dropColumn('observed_by_id');
            $table->dropColumn('identified_by_id');
        });
    }
}
