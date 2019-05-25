<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('field_observations', function (Blueprint $table) {
            $table->dropForeign(['observed_by_id']);
            $table->dropForeign(['identified_by_id']);

            $table->foreign('observed_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->foreign('identified_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });

        Schema::table('observations', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);

            $table->unsignedInteger('created_by_id')->nullable()->change();

            $table->foreign('created_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
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

            $table->foreign('observed_by_id')
                  ->references('id')
                  ->on('users');

            $table->foreign('identified_by_id')
                  ->references('id')
                  ->on('users');
        });

        Schema::table('observations', function (Blueprint $table) {
            $table->dropForeign(['created_by_id']);

            $table->unsignedInteger('created_by_id')->change();

            $table->foreign('created_by_id')
                  ->references('id')
                  ->on('users');
        });
    }
}
