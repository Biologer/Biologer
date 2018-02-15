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
            $table->string('location')->nullable();
            $table->double('latitude', 15, 12);
            $table->double('longitude', 15, 12);
            $table->unsignedInteger('accuracy')->nullable();
            $table->string('mgrs10k')->nullable();
            $table->smallInteger('elevation')->default(0);
            $table->string('observer')->nullable();
            $table->string('identifier')->nullable();
            $table->string('sex')->nullable();
            $table->unsignedInteger('stage_id')->nullable();
            $table->text('note')->nullable();
            $table->unsignedInteger('number')->nullable();
            $table->string('project')->nullable();
            $table->string('found_on')->nullable();
            $table->morphs('details');
            $table->dateTime('approved_at')->nullable();
            $table->unsignedInteger('created_by_id');
            $table->timestamps();

            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('stage_id')->references('id')->on('stages');

            $table->index('approved_at');
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
