<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewGroupTaxonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('view_group_taxon', function (Blueprint $table) {
            $table->unsignedInteger('view_group_id');
            $table->unsignedInteger('taxon_id');

            $table->primary(['view_group_id', 'taxon_id']);

            $table->foreign('view_group_id')
                ->references('id')->on('view_groups')
                ->onDelete('cascade');

            $table->foreign('taxon_id')
                ->references('id')->on('taxa')
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
        Schema::dropIfExists('view_group_taxon');
    }
}
