<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewGroupTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('view_group_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('view_group_id');
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();

            $table->unique(['view_group_id', 'locale']);

            $table->foreign('view_group_id')
                  ->references('id')
                  ->on('view_group')
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
        Schema::dropIfExists('view_group_translations');
    }
}
