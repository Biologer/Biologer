<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_observations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('collection_id');
            $table->string('preparator')->nullable();
            $table->time('time')->nullable();
            $table->string('original_date')->nullable();
            $table->string('original_locality')->nullable();
            $table->string('original_elevation')->nullable();
            $table->string('original_coordinates')->nullable();
            $table->tinyInteger('original_identification_validity');
            $table->text('other_original_data')->nullable();
            $table->string('georeferenced_by')->nullable();
            $table->date('georeferenced_date')->nullable();
            $table->smallInteger('minimum_elevation')->nullable();
            $table->smallInteger('maximum_elevation')->nullable();
            $table->unsignedSmallInteger('collecting_start_year')->nullable();
            $table->unsignedTinyInteger('collecting_start_month')->nullable();
            $table->unsignedSmallInteger('collecting_end_year')->nullable();
            $table->unsignedTinyInteger('collecting_end_month')->nullable();

            $table->timestamps();

            $table->foreign('collection_id')
                ->references('id')
                ->on('specimen_collections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_observations');
    }
}
