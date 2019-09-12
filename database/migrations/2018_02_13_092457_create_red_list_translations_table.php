<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedListTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('red_list_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('red_list_id');
            $table->string('locale');
            $table->string('name');

            $table->foreign('red_list_id')
                  ->references('id')
                  ->on('red_lists')
                  ->onDelete('cascade');

            $table->unique(['red_list_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('red_list_translations');
    }
}
