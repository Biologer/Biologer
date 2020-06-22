<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTaxaTableAddBirds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxa', function (Blueprint $table) {
            $table->dropColumn('fe_old_id');

            $table->string('spid', 10)->after('invasive')->unique();
            $table->integer('birdlife_seq')->after('spid')->unique();
            $table->integer('birdlife_id')->after('birdlife_seq')->unique();
            $table->integer('ebba_code')->after('birdlife_id');
            $table->integer('euring_code')->after('ebba_code');
            $table->string('euring_sci_name', 100)->after('euring_code')->unique();
            $table->string('eunis_n2000code', 10)->after('euring_sci_name')->nullable();
            $table->string('eunis_sci_name', 100)->after('eunis_n2000code')->unique()->nullable();
            $table->string('bioras_sci_name',200)->after('eunis_sci_name')->nullable();
            $table->tinyInteger('refer')->after('bioras_sci_name')->nullable();
            $table->enum('prior', [null,'PR','PR+'])->after('refer')->nullable();
            $table->string('sg', 10)->after('prior')->nullable();
            $table->string('gn_status', 10)->after('sg')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxa', function (Blueprint $table) {
            $table->dropColumn('spid');
            $table->dropColumn('birdlife_seq');
            $table->dropColumn('birdlife_id');
            $table->dropColumn('ebba_code');
            $table->dropColumn('euring_code');
            $table->dropColumn('euring_sci_name');
            $table->dropColumn('eunis_n2000code');
            $table->dropColumn('eunis_sci_name');
            $table->dropColumn('bioras_sci_name');
            $table->dropColumn('refer');
            $table->dropColumn('prior');
            $table->dropColumn('sg');
            $table->dropColumn('gn_status');

            $table->unsignedInteger('fe_old_id')->nullable();
        });
    }
}
