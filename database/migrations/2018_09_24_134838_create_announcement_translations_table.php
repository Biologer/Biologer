<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcement_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('announcement_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('message');

            $table->unique(['announcement_id', 'locale']);

            $table->foreign('announcement_id')
                  ->references('id')
                  ->on('announcements')
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
        Schema::dropIfExists('announcement_translations');
    }
}
