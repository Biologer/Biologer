<?php

namespace Tests\Feature\Api;

use App\DEM\Reader;
use App\Models\User;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GetElevationTest extends TestCase
{
    #[Test]
    public function can_calculate_elevations_based_on_latitude_and_longitude()
    {
        $fakeReader = new class implements Reader {
            public function getElevation($latitude, $longitude)
            {
                return 300;
            }
        };

        app()->instance(Reader::class, $fakeReader);

        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/elevation', [
            'latitude' => 44.668652872273185,
            'longitude' => 20.553617477416992,
        ]);

        $this->assertEquals([
            'elevation' => 300,
        ], $response->json());
    }
}
