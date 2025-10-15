<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimedCountObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timed_count_observations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();

            $table->smallInteger('year')->nullable();
            $table->unsignedTinyInteger('month')->nullable();
            $table->unsignedTinyInteger('day')->nullable();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedInteger('count_duration')->nullable();
            $table->unsignedSmallInteger('cloud_cover')->nullable();
            $table->unsignedDouble('atmospheric_pressure')->nullable();
            $table->unsignedInteger('humidity')->nullable();
            $table->double('temperature')->nullable();
            $table->enum('wind_direction', ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'])->nullable();
            $table->unsignedSmallInteger('wind_speed')->nullable();
            $table->text('habitat')->nullable();
            $table->text('comments')->nullable();
            $table->unsignedInteger('area')->nullable();
            $table->unsignedInteger('route_length')->nullable();
            $table->string('observer')->nullable();
            $table->unsignedInteger('observed_by_id')->nullable();
            $table->unsignedInteger('created_by_id')->nullable();
            $table->unsignedInteger('view_groups_id');
            $table->timestamps();

            $table->foreign('observed_by_id')->references('id')->on('users');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('view_groups_id')->references('id')->on('view_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timed_count_observations');
    }
}
