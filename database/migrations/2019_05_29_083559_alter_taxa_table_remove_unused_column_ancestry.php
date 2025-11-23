<?php

use App\Models\Taxon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTaxaTableRemoveUnusedColumnAncestry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxa', function (Blueprint $table) {
            $table->dropColumn('ancestry');
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
            $table->string('ancestry')->nullable()->after('author');
        });

        $this->rebuildAncestryCache();
    }

    /**
     * Rebuild ancestry cache based on connections to ancestors.
     *
     * @return void
     */
    protected function rebuildAncestryCache()
    {
        Taxon::with('ancestors')->each(function ($taxon) {
            $taxon->update([
                'ancestry' => $taxon->ancestors->sortByDesc('rank_level')->pluck('id')->push($taxon->id)->implode('/'),
            ]);
        });
    }
}
