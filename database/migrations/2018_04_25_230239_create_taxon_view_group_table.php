<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonViewGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxon_view_group', function (Blueprint $table) {
            $table->unsignedInteger('taxon_id');
            $table->unsignedInteger('view_group_id');

            $table->primary(['taxon_id', 'view_group_id']);

            $table->foreign('taxon_id')->references('id')->on('taxa')->onDelete('cascade');
            $table->foreign('view_group_id')->references('id')->on('view_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxon_view_group');
    }
}
