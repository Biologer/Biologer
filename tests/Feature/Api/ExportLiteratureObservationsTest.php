<?php

namespace Tests\Feature\Api;

use App\Exports\LiteratureObservations\CustomLiteratureObservationsExport;
use App\Jobs\PerformExport;
use App\User;
use Illuminate\Support\Facades\Queue;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ExportLiteratureObservationsTest extends TestCase
{
    /** @test */
    public function admin_can_export_all_observations()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'columns' => ['id', 'taxon'],
            'type' => 'custom',
            'with_header' => false,
        ]);

        $response->assertSuccessful();
        Queue::assertPushed(PerformExport::class, function ($job) use ($user) {
            return $job->export->type === CustomLiteratureObservationsExport::class
                && $job->export->user->is($user)
                && $job->export->filter->isEmpty()
                && $job->export->columns === ['id', 'taxon']
                && $job->export->with_header === false;
        });
    }

    /** @test */
    public function type_is_required()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/literature-observation-exports');

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function type_must_be_valid()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'type' => 'invalid',
        ]);

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function columns_are_required_to_perform_custom_export()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function columns_parameter_must_be_an_array()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'columns' => 'string',
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function columns_parameter_contain_at_least_one_column()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'columns' => [],
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function columns_are_supported()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'columns' => ['invalid'],
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }
}
