<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservationObservationTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observation_observation_type', function (Blueprint $table) {
            $table->unsignedBigInteger('observation_id');
            $table->unsignedInteger('type_id');

            $table->primary(['observation_id', 'type_id']);

            $table->foreign('observation_id')
                ->references('id')
                ->on('observations')
                ->onDelete('cascade');

            $table->foreign('type_id')
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
        Schema::dropIfExists('observation_observation_type');
    }
}
