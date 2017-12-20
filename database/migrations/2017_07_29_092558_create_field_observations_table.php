<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_observations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('source', 255)->nullable();
            $table->string('taxon_suggestion', 255)->nullable();
            $table->text('dynamic_fields')->nullable();
            $table->unsignedSmallInteger('license');
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
        Schema::dropIfExists('field_observations');
    }
}
