<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewGroupsTableAddOnlyObservedTaxaColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('view_groups', function (Blueprint $table) {
            $table->boolean('only_observed_taxa')->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('view_groups', function (Blueprint $table) {
            $table->dropColumn('only_observed_taxa');
        });
    }
}
