<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonAncestorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxon_ancestors', function (Blueprint $table) {
            $table->unsignedInteger('model_id');
            $table->unsignedInteger('ancestor_id');

            $table->primary(['model_id', 'ancestor_id']);

            $table->foreign('model_id')
                ->references('id')->on('taxa')
                ->onDelete('cascade');

            $table->foreign('ancestor_id')
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
        Schema::dropIfExists('taxon_ancestors');
    }
}
