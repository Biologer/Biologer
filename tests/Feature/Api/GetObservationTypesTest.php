<?php

namespace Tests\Feature\Api;

use App\ObservationType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GetObservationTypesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        Passport::actingAs(factory(User::class)->create());

        ObservationType::create([
            'slug' => 'test',
            'en' => [
                'name' => 'Test',
            ],
        ]);

        $response = $this->get('/api/observation-types');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'slug' => 'test',
        ]);
        $response->assertJsonFragment([
            'locale'=> 'en',
            'name' => 'Test',
        ]);
    }
}
