<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransectVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transect_visits', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedSmallInteger('cloud_cover')->nullable();
            $table->unsignedDouble('atmospheric_pressure')->nullable();
            $table->unsignedInteger('humidity')->nullable();
            $table->double('temperature')->nullable();
            $table->enum('wind_direction', ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'])->nullable();
            $table->unsignedSmallInteger('wind_speed')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedInteger('created_by_id')->nullable();
            $table->unsignedInteger('view_groups_id');
            $table->unsignedBigInteger('transect_section_id');
            $table->timestamps();

            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('view_groups_id')->references('id')->on('view_groups');
            $table->foreign('transect_section_id')->references('id')->on('transect_sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transect_visits');
    }
}
