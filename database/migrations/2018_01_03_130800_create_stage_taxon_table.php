<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStageTaxonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage_taxon', function (Blueprint $table) {
            $table->unsignedInteger('stage_id');
            $table->unsignedInteger('taxon_id');

            $table->primary(['stage_id', 'taxon_id']);

            $table->foreign('stage_id')
                ->references('id')
                ->on('stages')
                ->onDelete('cascade');

            $table->foreign('taxon_id')
                ->references('id')
                ->on('taxa')
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
        Schema::dropIfExists('stage_taxon');
    }
}
