<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedInteger('taxon_id');
            $table->unsignedInteger('ancestor_id');

            $table->primary(['taxon_id', 'ancestor_id']);
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
