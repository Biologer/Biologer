<?php

namespace Tests\Feature\Api;

use App\Exports\FieldObservations\CustomFieldObservationsExport;
use App\Exports\FieldObservations\DarwinCoreFieldObservationsExport;
use App\Jobs\PerformExport;
use App\User;
use Illuminate\Support\Facades\Queue;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ExportAllFieldObservationsTest extends TestCase
{
    /** @test */
    public function admin_can_export_all_observations()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/field-observation-exports', [
            'columns' => ['id', 'taxon'],
            'type' => 'custom',
            'with_header' => false,
        ]);

        $response->assertSuccessful();
        Queue::assertPushed(PerformExport::class, function ($job) use ($user) {
            return $job->export->type === CustomFieldObservationsExport::class
                && $job->export->user->is($user)
                && $job->export->filter->isEmpty()
                && $job->export->columns === ['id', 'taxon']
                && $job->export->with_header === false;
        });
    }

    /** @test */
    public function admin_can_export_all_observations_in_darwin_core_format()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/field-observation-exports', [
            'type' => 'darwin_core',
        ]);

        $response->assertSuccessful();
        Queue::assertPushed(PerformExport::class, function ($job) use ($user) {
            return $job->export->type === DarwinCoreFieldObservationsExport::class &&
                $job->export->user->is($user) &&
                $job->export->filter->isEmpty() &&
                $job->export->columns === DarwinCoreFieldObservationsExport::availableColumns()->all() &&
                $job->export->with_header === true;
        });
    }

    /** @test */
    public function type_is_required()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/field-observation-exports');

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function type_must_be_valid()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/field-observation-exports', [
            'type' => 'invalid',
        ]);

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function columns_are_required_to_perform_custom_export()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/field-observation-exports', [
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function columns_parameter_must_be_an_array()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/field-observation-exports', [
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
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/field-observation-exports', [
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
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/field-observation-exports', [
            'columns' => ['invalid'],
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }
}
