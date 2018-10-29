<?php

namespace Tests\Feature\Api;

use App\User;
use Tests\TestCase;
use App\Jobs\PerformExport;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exports\FieldObservations\ContributorFieldObservationsCustomExport;
use App\Exports\FieldObservations\ContributorFieldObservationsDarwinCoreExport;

class ExportContributorFieldObservationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function contributors_can_export_their_observations()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/my/field-observations/export', [
            'columns' => ['id', 'taxon'],
            'with_header' => false,
            'type' => 'custom',
        ]);

        $response->assertSuccessful();
        Queue::assertPushed(PerformExport::class, function ($job) use ($user) {
            return $job->export->type === ContributorFieldObservationsCustomExport::class
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

        $response = $this->postJson('/api/my/field-observations/export');

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function type_must_be_valid()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/my/field-observations/export', [
            'type' => 'invalid',
        ]);

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function columns_are_required_to_perform_the_export()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/my/field-observations/export', [
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

        $response = $this->postJson('/api/my/field-observations/export', [
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

        $response = $this->postJson('/api/my/field-observations/export', [
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

        $response = $this->postJson('/api/my/field-observations/export', [
            'columns' => ['invalid'],
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    /** @test */
    public function contributors_can_export_their_observations_using_darwin_core_standard()
    {
        Queue::fake();
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/my/field-observations/export', [
            'type' => 'darwin_core',
        ]);

        $response->assertSuccessful();
        Queue::assertPushed(PerformExport::class, function ($job) use ($user) {
            return $job->export->type === ContributorFieldObservationsDarwinCoreExport::class &&
                $job->export->user->is($user) &&
                $job->export->filter->isEmpty() &&
                $job->export->columns === ContributorFieldObservationsDarwinCoreExport::availableColumns()->all() &&
                $job->export->with_header === true;
        });
    }
}
