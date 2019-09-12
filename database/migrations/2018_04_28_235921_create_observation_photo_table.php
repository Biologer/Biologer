<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservationPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observation_photo', function (Blueprint $table) {
            $table->unsignedBigInteger('observation_id');
            $table->unsignedBigInteger('photo_id');

            $table->primary(['observation_id', 'photo_id']);

            $table->foreign('observation_id')
                  ->references('id')
                  ->on('observations')
                  ->onDelete('cascade');

            $table->foreign('photo_id')
                  ->references('id')
                  ->on('photos')
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
        Schema::dropIfExists('observation_photo');
    }
}
