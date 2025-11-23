<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use App\ObservationType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;
use Tests\TestCase;

final class GetObservationTypesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function get_observations_types(): void
    {
        Passport::actingAs(User::factory()->create());

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

    #[Test]
    public function can_be_filtered_to_get_only_those_updated_after_given_timestamp(): void
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(User::factory()->create());

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
