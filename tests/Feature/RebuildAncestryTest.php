<?php

namespace Tests\Feature;

use App\Taxon;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RebuildAncestryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rebuilding_ancestry()
    {
        $class = factory(Taxon::class)->create([
            'parent_id' => null,
            'rank' => 'class',
            'ancestry' => '',
        ]);

        $family = factory(Taxon::class)->create([
            'parent_id' => $class->id,
            'rank' => 'family',
            'ancestry' => '',
        ]);

        $genus = factory(Taxon::class)->create([
            'parent_id' => $family->id,
            'rank' => 'genus',
            'ancestry' => '',
        ]);

        $taxa = [$class, $family, $genus];

        $this->artisan('ancestry:rebuild');

        $class->ancestors->assertCount(0);
        $this->assertEmpty($class->fresh()->ancestry);

        $family->ancestors->assertCount(1);
        $family->ancestors->assertContains($class);
        $this->assertEquals($class->id, $family->fresh()->ancestry);

        $genus->ancestors->assertCount(2);
        $genus->ancestors->assertContains($class);
        $genus->ancestors->assertContains($family);
        $this->assertEquals("{$class->id}/{$family->id}", $genus->fresh()->ancestry);
    }
}
