<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxa', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('name')->unique();
            $table->string('rank')->index();
            $table->unsignedInteger('rank_level')->index();
            $table->string('author')->nullable();
            $table->string('ancestry');
            $table->unsignedInteger('fe_old_id')->nullable();
            $table->string('fe_id')->nullable();
            $table->boolean('restricted')->default(false);
            $table->boolean('allochthonous')->default(false);
            $table->boolean('invasive')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxa');
    }
}
