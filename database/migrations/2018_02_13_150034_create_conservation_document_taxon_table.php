<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConservationDocumentTaxonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conservation_document_taxon', function (Blueprint $table) {
            $table->unsignedInteger('doc_id');
            $table->unsignedInteger('taxon_id');

            $table->primary(['doc_id', 'taxon_id']);

            $table->foreign('doc_id')
                ->references('id')
                ->on('conservation_documents')
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
        Schema::dropIfExists('conservation_document_taxon');
    }
}
