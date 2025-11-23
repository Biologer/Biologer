<?php

namespace Tests\Feature;

use App\Models\Taxon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RebuildAncestryTest extends TestCase
{
    #[Test]
    public function rebuilding_ancestry()
    {
        $class = Taxon::factory()->create([
            'parent_id' => null,
            'rank' => 'class',
        ]);

        $family = Taxon::factory()->create([
            'parent_id' => $class->id,
            'rank' => 'family',
        ]);

        $genus = Taxon::factory()->create([
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
