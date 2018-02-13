<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConservationListTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conservation_list_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('list_id');
            $table->string('locale');
            $table->string('name');
            $table->string('description')->nullable();

            $table->foreign('list_id')
                  ->references('id')
                  ->on('conservation_lists')
                  ->onDelete('cascade');

            $table->unique(['list_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conservation_list_translations');
    }
}
