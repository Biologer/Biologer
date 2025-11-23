<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use App\Exports\LiteratureObservations\CustomLiteratureObservationsExport;
use App\Jobs\PerformExport;
use App\User;
use Illuminate\Support\Facades\Queue;
use Laravel\Passport\Passport;
use Tests\TestCase;

final class ExportLiteratureObservationsTest extends TestCase
{
    #[Test]
    public function admin_can_export_all_observations(): void
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

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

    #[Test]
    public function type_is_required(): void
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/literature-observation-exports');

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function type_must_be_valid(): void
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'type' => 'invalid',
        ]);

        $response->assertJsonValidationErrors('type');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function columns_are_required_to_perform_custom_export(): void
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function columns_parameter_must_be_an_array(): void
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'columns' => 'string',
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function columns_parameter_contain_at_least_one_column(): void
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'columns' => [],
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }

    #[Test]
    public function columns_are_supported(): void
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/literature-observation-exports', [
            'columns' => ['invalid'],
            'type' => 'custom',
        ]);

        $response->assertJsonValidationErrors('columns');
        Queue::assertNotPushed(PerformExport::class);
    }
}
