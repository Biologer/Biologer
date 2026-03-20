<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransectCountObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transect_count_observations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('location')->nullable();
            $table->string('length')->nullable();   # should be calculatable from maps, so maybe not in string?
            $table->string('primary_habitat')->nullable();
            $table->unsignedInteger('created_by_id')->nullable();
            $table->timestamps();

            $table->foreign('created_by_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transect_count_observations');
    }
}
