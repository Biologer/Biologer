<?php

namespace Tests\Feature\Api;

use App\ObservationType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GetObservationTypesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_observations_types()
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
            'locale' => 'en',
            'name' => 'Test',
        ]);
    }

    /** @test */
    public function can_be_filtered_to_get_only_those_updated_after_given_timestamp()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(factory(User::class)->create());

        Date::setTestNow(now()->subDay());

        ObservationType::create(['slug' => 'test1']);

        Date::setTestNow();

        ObservationType::create(['slug' => 'test2']);

        $response = $this->get('/api/observation-types?updated_after='.now()->subHours(5)->timestamp);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'slug' => 'test2',
        ]);
        $response->assertJsonMissing([
            'slug' => 'test1',
        ]);
    }
}
