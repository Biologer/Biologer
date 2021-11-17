<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAncestorsNamesColumnToTaxaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxa', function (Blueprint $table) {
            $table->string('ancestors_names', 1000)->nullable()->before('created_at');
        });

        $subquery = DB::table(function ($query) {
            $query->select('id', 'name')->from('taxa')->orderBy('rank_level', 'desc');
        }, 'ancestors')
            ->selectRaw('GROUP_CONCAT(`ancestors`.`name`)')
            ->join('taxon_ancestors', 'ancestors.id', '=', 'taxon_ancestors.ancestor_id')
            ->whereColumn('taxon_ancestors.model_id', 'taxa.id');

        DB::table('taxa')->update([
            'ancestors_names' => DB::raw("({$subquery->toSql()})"),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxa', function (Blueprint $table) {
            $table->dropColumn('ancestors_names');
        });
    }
}
