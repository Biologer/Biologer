<?php

namespace Tests\Unit;

use App\Observation;
use App\Taxon;
use Tests\ObservationFactory;
use Tests\TestCase;

class TaxonTest extends TestCase
{
    /** @test */
    public function it_can_have_many_observations()
    {
        $taxon = factory(Taxon::class)->create();
        $this->assertCount(0, $taxon->observations);

        $observations = ObservationFactory::createManyFieldObservations(3, [
            'taxon_id' => $taxon->id,
        ])->pluck('observation');
        $taxon->load('observations');

        $this->assertCount(3, $taxon->observations);
        $taxon->observations->assertEquals(Observation::all());
    }

    /** @test */
    public function it_can_have_many_approved_observations()
    {
        $taxon = factory(Taxon::class)->create();

        $approvedObservations = ObservationFactory::createManyFieldObservations(2, [
            'taxon_id' => $taxon->id,
        ])->pluck('observation');

        $unapprovedObservations = ObservationFactory::createManyUnnapprovedFieldObservations(2, [
            'taxon_id' => $taxon->id,
        ])->pluck('observation');

        $approvedObservations->each(function ($observation) use ($taxon) {
            $taxon->approvedObservations->assertContains($observation);
        });
        $unapprovedObservations->each(function ($observation) use ($taxon) {
            $taxon->approvedObservations->assertDoesntContain($observation);
        });
    }

    /** @test */
    public function it_can_have_many_unapproved_observations()
    {
        $taxon = factory(Taxon::class)->create();

        $approvedObservations = ObservationFactory::createManyFieldObservations(2, [
            'taxon_id' => $taxon->id,
        ])->pluck('observation');

        $unapprovedObservations = ObservationFactory::createManyUnnapprovedFieldObservations(2, [
            'taxon_id' => $taxon->id,
        ])->pluck('observation');

        $unapprovedObservations->each(function ($observation) use ($taxon) {
            $taxon->unapprovedObservations->assertContains($observation);
        });
        $approvedObservations->each(function ($observation) use ($taxon) {
            $taxon->unapprovedObservations->assertDoesntContain($observation);
        });
    }

    /** @test */
    public function it_can_list_unique_mgrs_fields_of_approved_observations()
    {
        $taxon = factory(Taxon::class)->create();

        ObservationFactory::createManyFieldObservations(2, [
            'taxon_id' => $taxon->id,
            'mgrs10k' => 'EQ54',
        ]);
        ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
            'mgrs10k' => 'SA38',
        ]);
        ObservationFactory::createFieldObservation([
            'taxon_id' => $taxon->id,
            'mgrs10k' => 'AE13',
        ]);

        $this->assertEquals(
            ['AE13', 'EQ54'],
            $taxon->mgrs10k()->keys()->all(),
            'MGRS fields do not match.'
        );
    }

    /** @test */
    public function it_can_be_root_taxon()
    {
        $taxon = factory(Taxon::class)->create([
            'parent_id' => null,
        ]);

        $this->assertTrue($taxon->isRoot());
    }

    /** @test */
    public function it_can_be_child_of_another_taxon()
    {
        $parent = factory(Taxon::class)->create();
        $taxon = factory(Taxon::class)->create([
            'parent_id' => $parent->id,
        ]);

        $this->assertFalse($taxon->isRoot());
        $this->assertTrue($taxon->isChildOf($parent));
        $this->assertFalse($parent->isChildOf($taxon));
    }

    /** @test */
    public function it_can_be_parent_of_another_taxon()
    {
        $parent = factory(Taxon::class)->create();
        $taxon = factory(Taxon::class)->create([
            'parent_id' => $parent->id,
        ]);

        $this->assertTrue($parent->isParentOf($taxon));
        $this->assertFalse($taxon->isParentOf($parent));
    }

    /** @test */
    public function the_parent_is_ancestor_as_well()
    {
        $parent = factory(Taxon::class)->create(['parent_id' => null]);
        $taxon = factory(Taxon::class)->create(['parent_id' => $parent->id]);

        $taxon->ancestors->assertContains($parent);
    }

    /** @test */
    public function it_can_have_many_ancestors_which_it_inherits_from_parent()
    {
        $root = factory(Taxon::class)->create(['parent_id' => null]);
        $parent = factory(Taxon::class)->create(['parent_id' => $root->id]);
        $taxon = factory(Taxon::class)->create(['parent_id' => $parent->id]);

        $taxon->ancestors->assertContains($parent);
        $taxon->ancestors->assertContains($root);
    }

    /** @test */
    public function it_can_have_many_children_taxa()
    {
        $taxon = factory(Taxon::class)->create();
        $children = factory(Taxon::class, 3)->create(['parent_id' => $taxon->id]);

        $taxon->children->assertEquals($children);
        $children->each(function ($child) use ($taxon) {
            $this->assertTrue($child->isChildOf($taxon));
        });
    }

    /** @test */
    public function it_can_many_descendants()
    {
        $root = factory(Taxon::class)->create(['parent_id' => null]);
        $parent = factory(Taxon::class)->create(['parent_id' => $root->id]);
        $taxon = factory(Taxon::class)->create(['parent_id' => $parent->id]);

        $root->descendants->assertContains($parent);
        $root->descendants->assertContains($taxon);
        $parent->descendants->assertContains($taxon);
        $parent->descendants->assertDoesntContain($root);
    }

    /** @test */
    public function ancestors_are_linked_upon_creation()
    {
        $root = factory(Taxon::class)->create(['parent_id' => null]);
        $parent = factory(Taxon::class)->create(['parent_id' => $root->id]);
        $taxon = factory(Taxon::class)->create(['parent_id' => $parent->id]);

        $taxon->ancestors->assertContains($root);
        $taxon->ancestors->assertContains($parent);
    }

    /** @test */
    public function ancestry_for_descendants_is_relinked_when_taxon_parent_changes()
    {
        $root1 = factory(Taxon::class)->create(['parent_id' => null]);
        $root2 = factory(Taxon::class)->create(['parent_id' => null]);
        $parent = factory(Taxon::class)->create(['parent_id' => $root1->id]);
        $taxon = factory(Taxon::class)->create(['parent_id' => $parent->id]);

        $taxon->ancestors->assertDoesntContain($root2);

        $parent->fresh()->update(['parent_id' => $root2->id]);

        tap($parent->fresh(), function ($parent) use ($root2) {
            $parent->ancestors->assertContains($root2);
        });

        tap($taxon->fresh(), function ($taxon) use ($root1, $root2, $parent) {
            $taxon->ancestors->assertDoesntContain($root1);
            $taxon->ancestors->assertContains($root2);
            $taxon->ancestors->assertContains($parent);
        });
    }
}
