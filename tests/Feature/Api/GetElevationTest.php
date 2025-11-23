<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use App\DEM\Reader;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GetElevationTest extends TestCase
{
    #[Test]
    public function can_calculate_elevations_based_on_latitude_and_longitude(): void
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
