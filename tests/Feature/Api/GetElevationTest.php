<?php

namespace Tests\Feature\Api;

use App\User;
use Tests\TestCase;
use App\DEM\ReaderInterface;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetElevationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_calculate_elevations_based_on_latitude_and_longitude()
    {
        $fakeReader = new class implements ReaderInterface {
            public function getElevation($latitude, $longitude)
            {
                return 300;
            }
        };

        app()->instance(ReaderInterface::class, $fakeReader);

        Passport::actingAs(factory(User::class)->create());

        $response = $this->postJson('/api/elevation', [
            'latitude' => 44.668652872273185,
            'longitude' => 20.553617477416992,
        ]);

        $this->assertEquals([
            'elevation' => 300,
        ], $response->json());
    }
}
