<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('view_group_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->text('description')->nullable();

            $table->unique(['view_group_id','locale']);

            $table->foreign('view_group_id')
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
        Schema::dropIfExists('view_group_translations');
    }
}
