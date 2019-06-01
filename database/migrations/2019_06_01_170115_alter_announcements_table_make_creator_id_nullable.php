<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAnnouncementsTableMakeCreatorIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->unsignedInteger('creator_id')->nullable()->change();

            if ($this->isNotUsingSqlite()) {
                $table->foreign('creator_id')
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
        Schema::table('announcements', function (Blueprint $table) {
            if ($this->isNotUsingSqlite()) {
                $table->dropForeign(['creator_id']);
            }

            $table->unsignedInteger('creator_id')->change();
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
