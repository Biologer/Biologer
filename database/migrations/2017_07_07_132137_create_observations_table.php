<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('taxon_id')->nullable();
            $table->string('year');
            $table->char('month', 2)->nullable();
            $table->char('day', 2)->nullable();
            $table->string('location');
            $table->float('latitude');
            $table->float('longitude');
            $table->unsignedInteger('accuracy')->default(1);
            $table->string('mgrs_field')->nullable();
            $table->smallInteger('altitude')->default(0);
            $table->dateTime('approved_at')->nullable();
            $table->unsignedInteger('created_by_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('observations');
    }
}
