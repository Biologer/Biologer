<?php

namespace Tests\Unit;

use App\Taxon;
use Tests\TestCase;
use App\Observation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TaxonTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function can_have_many_observations()
    {
        $taxon = factory(Taxon::class)->create();
        $this->assertCount(0, $taxon->observations);

        $observations = factory(Observation::class, 3)->create([
            'taxon_id' => $taxon->id,
        ]);
        $taxon->load('observations');

        $this->assertCount(3, $taxon->observations);
        $taxon->observations->assertEquals(Observation::all());
    }

    /** @test */
    function can_have_many_approved_observations()
    {
        $taxon = factory(Taxon::class)->create();

        $approvedObservations = factory(Observation::class, 2)->create([
            'taxon_id' => $taxon->id,
        ]);
        $unapprovedObservations = factory(Observation::class, 2)->states('unapproved')->create([
            'taxon_id' => $taxon->id,
        ]);

        $approvedObservations->each(function ($observation) use ($taxon) {
            $taxon->approvedObservations->assertContains($observation);
        });
        $unapprovedObservations->each(function ($observation) use ($taxon) {
            $taxon->approvedObservations->assertNotContains($observation);
        });
    }

    /** @test */
    function can_have_many_unapproved_observations()
    {
        $taxon = factory(Taxon::class)->create();

        $unapprovedObservations = factory(Observation::class, 2)->states('unapproved')->create([
            'taxon_id' => $taxon->id,
        ]);
        $approvedObservations = factory(Observation::class, 2)->create([
            'taxon_id' => $taxon->id,
        ]);

        $unapprovedObservations->each(function ($observation) use ($taxon) {
            $taxon->unapprovedObservations->assertContains($observation);
        });
        $approvedObservations->each(function ($observation) use ($taxon) {
            $taxon->unapprovedObservations->assertNotContains($observation);
        });
    }

    /** @test */
    function can_list_unique_mgrs_fields_of_approved_observations()
    {
        $taxon = factory(Taxon::class)->create();
        factory(Observation::class, 2)->create([
            'taxon_id' => $taxon->id,
            'mgrs_field' => '54EQ',
        ]);
        factory(Observation::class)->create([
            'taxon_id' => $taxon->id,
            'mgrs_field' => '13AE',
        ]);

        $this->assertEquals(
            ['54EQ', '13AE'], $taxon->mgrs(), 'MGRS fields do not match.'
        );
    }
}
