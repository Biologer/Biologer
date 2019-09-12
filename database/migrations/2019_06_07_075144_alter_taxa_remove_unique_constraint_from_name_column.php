<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTaxaRemoveUniqueConstraintFromNameColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxa', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxa', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->unique(['name']);
        });
    }
}
