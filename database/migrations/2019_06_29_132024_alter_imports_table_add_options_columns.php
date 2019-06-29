<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterImportsTableAddOptionsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->text('options')->nullable()->after('has_heading');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
}
