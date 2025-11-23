<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use App\Taxon;
use App\ViewGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ObservationFactory;
use Tests\TestCase;

class ViewGroupTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function get_first_species_in_group(): void
    {
        $rootGroup = ViewGroup::factory()->create();
        $group = ViewGroup::factory()->create(['parent_id' => $rootGroup->id]);
        $genus = Taxon::factory()->create(['rank' => 'genus']);
        $species = Taxon::factory()->create(['rank' => 'species', 'parent_id' => $genus->id]);
        $group->taxa()->attach($genus);

        $groupWithSpecies = ViewGroup::withFirstSpecies()->find($group->id);

        $this->assertTrue($groupWithSpecies->firstSpecies->is($species));
    }

    #[Test]
    public function get_first_species_in_group_that_shows_only_observed_species(): void
    {
        $group = ViewGroup::factory()->create([
            'parent_id' => ViewGroup::factory()->create()->id,
            'only_observed_taxa' => true,
        ]);
        $genus = Taxon::factory()->create(['rank' => 'genus']);
        $species1 = Taxon::factory()->create(['rank' => 'species', 'parent_id' => $genus->id, 'name' => 'A']);
        $species2 = Taxon::factory()->create(['rank' => 'species', 'parent_id' => $genus->id, 'name' => 'B']);
        ObservationFactory::createFieldObservation(['taxon_id' => $species2->id]);
        $group->taxa()->attach($genus);

        $groupWithSpecies = ViewGroup::withFirstSpecies()->find($group->id);

        $this->assertTrue($groupWithSpecies->firstSpecies->is($species2));
        $this->assertFalse($groupWithSpecies->firstSpecies->is($species1));
    }
}
