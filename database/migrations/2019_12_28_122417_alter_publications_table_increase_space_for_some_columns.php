<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPublicationsTableIncreaseSpaceForSomeColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->text('name')->nullable()->change();
            $table->text('title')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('title')->change();
        });
    }
}
