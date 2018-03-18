<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObservationTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observation_type_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('locale')->index();
            $table->unsignedInteger('observation_type_id');
            $table->string('name');

            $table->foreign('observation_type_id')
                  ->references('id')
                  ->on('observation_types')
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
        Schema::dropIfExists('observation_type_translations');
    }
}
