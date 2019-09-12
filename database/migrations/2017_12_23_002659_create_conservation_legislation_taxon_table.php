<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConservationLegislationTaxonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conservation_legislation_taxon', function (Blueprint $table) {
            $table->unsignedInteger('leg_id');
            $table->unsignedInteger('taxon_id');

            $table->primary(['leg_id', 'taxon_id']);

            $table->foreign('leg_id')
                ->references('id')
                ->on('conservation_legislations')
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
        Schema::dropIfExists('conservation_legislation_taxon');
    }
}
