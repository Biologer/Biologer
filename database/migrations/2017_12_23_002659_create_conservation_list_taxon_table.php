<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConservationListTaxonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conservation_list_taxon', function (Blueprint $table) {
            $table->unsignedInteger('conservation_list_id');
            $table->unsignedInteger('taxon_id');

            $table->primary(['conservation_list_id', 'taxon_id']);

            $table->foreign('conservation_list_id')
                ->references('id')
                ->on('conservation_lists')
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
        Schema::dropIfExists('conservation_list_taxon');
    }
}
