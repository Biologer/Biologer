<?php

use App\ConservationLegislation;
use Illuminate\Database\Migrations\Migration;

class UpdateConservationLegislations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ConservationLegislation::where('slug', 'bern')->update(['slug' => 'bern-2']);
        ConservationLegislation::where('slug', 'cites')->update(['slug' => 'cites-2']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ConservationLegislation::where('slug', 'bern-2')->update(['slug' => 'bern']);
        ConservationLegislation::where('slug', 'cites-2')->update(['slug' => 'cites']);
    }
}
