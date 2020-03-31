<?php

namespace Tests\Feature;

use App\Taxon;
use App\ViewGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ObservationFactory;
use Tests\TestCase;

class ViewGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_first_species_in_group()
    {
        $rootGroup = factory(ViewGroup::class)->create();
        $group = factory(ViewGroup::class)->create(['parent_id' => $rootGroup->id]);
        $genus = factory(Taxon::class)->create(['rank' => 'genus']);
        $species = factory(Taxon::class)->create(['rank' => 'species', 'parent_id' => $genus->id]);
        $group->taxa()->attach($genus);

        $this->assertTrue($group->firstSpecies()->is($species));
    }

    /** @test */
    public function get_first_species_in_group_that_shows_only_observed_species()
    {
        $group = factory(ViewGroup::class)->create([
            'parent_id' => factory(ViewGroup::class)->create()->id,
            'only_observed_taxa' => true,
        ]);
        $genus = factory(Taxon::class)->create(['rank' => 'genus']);
        $species1 = factory(Taxon::class)->create(['rank' => 'species', 'parent_id' => $genus->id, 'name' => 'A']);
        $species2 = factory(Taxon::class)->create(['rank' => 'species', 'parent_id' => $genus->id, 'name' => 'B']);
        ObservationFactory::createFieldObservation(['taxon_id' => $species2->id]);
        $group->taxa()->attach($genus);

        $this->assertTrue($group->firstSpecies()->is($species2));
        $this->assertFalse($group->firstSpecies()->is($species1));
    }
}
