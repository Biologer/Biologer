<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use App\Exports\FieldObservations\CuratorApprovedFieldObservationsCustomExport;
use App\Exports\FieldObservations\CuratorApprovedFieldObservationsDarwinCoreExport;
use App\Jobs\PerformExport;
use App\User;
use Illuminate\Support\Facades\Queue;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ExportCuratorApprovedFieldObservationsTest extends TestCase
{
    #[Test]
    public function curator_can_export_approved_field_observations_they_curate()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/approved-observations/export', [
            'columns' => ['id', 'taxon'],
            'with_header' => false,
            'type' => 'custom',
        ]);

        $response->assertSuccessful();
        Queue::assertPushed(PerformExport::class, function ($job) use ($user) {
            return $job->export->type === CuratorApprovedFieldObservationsCustomExport::class
                && $job->export->user->is($user)
                && $job->export->filter->isEmpty()
                && $job->export->columns === ['id', 'taxon']
                && $job->export->with_header === false;
        });
    }

    #[Test]
    public function type_is_required()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/approved-observations/export');

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function type_must_be_valid()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/approved-observations/export', [
            'type' => 'invalid',
        ]);

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function columns_are_required_to_perform_the_export()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/approved-observations/export', [
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function columns_parameter_must_be_an_array()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/approved-observations/export', [
            'columns' => 'string',
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function columns_parameter_contain_at_least_one_column()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/approved-observations/export', [
            'columns' => [],
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function columns_are_supported()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/approved-observations/export', [
            'columns' => ['invalid'],
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function curator_can_export_approved_observations_using_darwin_core_standard()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/curator/approved-observations/export', [
            'type' => 'darwin_core',
        ]);

        $response->assertSuccessful();
        Queue::assertPushed(PerformExport::class, function ($job) use ($user) {
            return $job->export->type === CuratorApprovedFieldObservationsDarwinCoreExport::class &&
                $job->export->user->is($user) &&
                $job->export->filter->isEmpty() &&
                $job->export->columns === CuratorApprovedFieldObservationsDarwinCoreExport::availableColumns()->all() &&
                $job->export->with_header === true;
        });
    }
}
