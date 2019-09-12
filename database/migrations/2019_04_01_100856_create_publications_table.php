<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->unsignedSmallInteger('year');
            $table->text('authors')->nullable();
            $table->text('editors')->nullable();
            $table->string('name')->nullable();
            $table->string('title');
            $table->string('issue')->nullable();
            $table->string('place')->nullable();
            $table->string('publisher')->nullable();
            $table->unsignedSmallInteger('page_count')->nullable();
            $table->string('page_range')->nullable();
            $table->string('doi')->nullable();
            $table->text('link')->nullable();
            $table->unsignedInteger('attachment_id')->nullable();
            $table->unsignedInteger('created_by_id')->nullable();
            $table->text('citation')->nullable();
            $table->timestamps();

            $table->foreign('created_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publications');
    }
}
