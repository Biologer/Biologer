<?php

namespace Tests\Feature\Api;

use App\Exports\FieldObservations\CuratorPendingFieldObservationsCustomExport;
use App\Exports\FieldObservations\CuratorPendingFieldObservationsDarwinCoreExport;
use App\Jobs\PerformExport;
use App\User;
use Illuminate\Support\Facades\Queue;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ExportCuratorPendingFieldObservationsTest extends TestCase
{
    /** @test */
    public function curator_can_export_pending_field_observations_they_curate()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/pending-observations/export', [
            'columns' => ['id', 'taxon'],
            'with_header' => false,
            'type' => 'custom',
        ]);

        $response->assertSuccessful();
        Queue::assertPushed(PerformExport::class, function ($job) use ($user) {
            return $job->export->type === CuratorPendingFieldObservationsCustomExport::class
                && $job->export->user->is($user)
                && $job->export->filter->isEmpty()
                && $job->export->columns === ['id', 'taxon']
                && $job->export->with_header === false;
        });
    }

    /** @test */
    public function columns_are_required_to_perform_the_export()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/pending-observations/export', [
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function type_is_required()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/pending-observations/export');

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function type_must_be_valid()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/pending-observations/export', [
            'type' => 'invalid',
        ]);

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function columns_parameter_must_be_an_array()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/pending-observations/export', [
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

        $response = $this->postJson('/api/curator/pending-observations/export', [
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

        $response = $this->postJson('/api/curator/pending-observations/export', [
            'columns' => ['invalid'],
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function curator_can_export_pending_observations_using_darwin_core_standard()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/pending-observations/export', [
            'type' => 'darwin_core',
        ]);

        $response->assertSuccessful();
        Queue::assertPushed(PerformExport::class, function ($job) use ($user) {
            return $job->export->type === CuratorPendingFieldObservationsDarwinCoreExport::class &&
                $job->export->user->is($user) &&
                $job->export->filter->isEmpty() &&
                $job->export->columns === CuratorPendingFieldObservationsDarwinCoreExport::availableColumns()->all() &&
                $job->export->with_header === true;
        });
    }
}
