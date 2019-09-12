<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxon_user', function (Blueprint $table) {
            $table->unsignedInteger('taxon_id');
            $table->unsignedInteger('user_id');

            $table->primary(['taxon_id', 'user_id']);

            $table->foreign('taxon_id')
                ->references('id')
                ->on('taxa')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxon_user');
    }
}
