<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConservationLegislationTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conservation_legislation_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('leg_id');
            $table->string('locale');
            $table->string('name');
            $table->string('description')->nullable();

            $table->foreign('leg_id')
                  ->references('id')
                  ->on('conservation_legislations')
                  ->onDelete('cascade');

            $table->unique(['leg_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conservation_legislation_translations');
    }
}
