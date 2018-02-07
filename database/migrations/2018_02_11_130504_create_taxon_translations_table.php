<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxonTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxon_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('taxon_id')->unsigned();
            $table->string('locale')->index();
            $table->string('native_name')->nullable();
            $table->text('description')->nullable();

            $table->unique(['taxon_id','locale']);

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
        Schema::dropIfExists('taxon_translations');
    }
}
