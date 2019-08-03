<?php

namespace Tests\Feature;

use App\Taxon;
use Tests\TestCase;

class RebuildAncestryTest extends TestCase
{
    /** @test */
    public function rebuilding_ancestry()
    {
        $class = factory(Taxon::class)->create([
            'parent_id' => null,
            'rank' => 'class',
        ]);

        $family = factory(Taxon::class)->create([
            'parent_id' => $class->id,
            'rank' => 'family',
        ]);

        $genus = factory(Taxon::class)->create([
            'parent_id' => $family->id,
            'rank' => 'genus',
        ]);

        $taxa = [$class, $family, $genus];

        $this->artisan('ancestry:rebuild');

        $class->ancestors->assertCount(0);

        $family->ancestors->assertCount(1);
        $family->ancestors->assertContains($class);

        $genus->ancestors->assertCount(2);
        $genus->ancestors->assertContains($class);
        $genus->ancestors->assertContains($family);
    }
}
