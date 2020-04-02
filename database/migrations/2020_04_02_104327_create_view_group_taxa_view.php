<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateViewGroupTaxaView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->dropView());
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }

    protected function dropView()
    {
        return <<<SQL
DROP VIEW IF EXISTS `view_group_taxa`;
SQL;
    }

    protected function createView()
    {
        $query = DB::table('taxa')
            ->join('taxon_view_group', 'taxon_view_group.taxon_id', '=', 'taxa.id')
            ->select('taxa.id as taxon_id', 'taxon_view_group.view_group_id')
            ->union(function ($query) {
                $query->select('ancestors.id as taxon_id', 'taxon_view_group.view_group_id')
                    ->from('taxa as ancestors')
                    ->join('taxon_ancestors', 'taxon_ancestors.model_id', '=', 'ancestors.id')
                    ->join('taxon_view_group', 'taxon_ancestors.ancestor_id', '=', 'taxon_view_group.taxon_id');
            })
            ->toSql();

        return <<<SQL
CREATE VIEW `view_group_taxa` AS $query
SQL;
    }
}
