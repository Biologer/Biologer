<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ($this->isNotUsingSqlite()) {
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
        }

        Schema::table('observations', function (Blueprint $table) {
            if ($this->isNotUsingSqlite()) {
                $table->dropForeign(['created_by_id']);
            }

            $table->unsignedInteger('created_by_id')->nullable()->change();

            if ($this->isNotUsingSqlite()) {
                $table->foreign('created_by_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if ($this->isNotUsingSqlite()) {
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
        }

        Schema::table('observations', function (Blueprint $table) {
            if ($this->isNotUsingSqlite()) {
                $table->dropForeign(['created_by_id']);
            }

            $table->unsignedInteger('created_by_id')->change();

            if ($this->isNotUsingSqlite()) {
                $table->foreign('created_by_id')
                      ->references('id')
                      ->on('users');
            }
        });
    }

    /**
     * Check if SQLite is not used.
     *
     * @return bool
     */
    protected function isNotUsingSqlite()
    {
        return DB::getDriverName() !== 'sqlite';
    }
}
