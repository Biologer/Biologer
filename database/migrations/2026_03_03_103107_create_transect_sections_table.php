<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransectSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transect_sections', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('length')->nullable();   # should be calculatable from maps, so maybe not in string?
            $table->string('primary_habitat')->nullable();
            $table->string('secondary_habitat')->nullable();
            $table->string('land_tenure')->nullable();
            $table->string('land_management')->nullable();
            $table->unsignedBigInteger('transect_count_observation_id');
            $table->timestamps();
            $table->foreign('transect_count_observation_id')->references('id')->on('transect_count_observations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transect_sections');
    }
}
