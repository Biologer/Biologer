<?php

namespace Tests\Feature\Api;

use App\Import;
use App\Importing\ImportStatus;
use App\Jobs\ProcessImport;
use App\Publication;
use App\User;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ImportingLiteratureObservationsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
    }

    /**
     * Make sample valid file.
     *
     * @param  string  $contents
     * @return \Illuminate\Http\Testing\File
     */
    protected function validFile($contents = null)
    {
        $file = File::fake()->create('import.csv');

        file_put_contents($file->getPathname(), $contents ?? '21.1212,42.12121,350,2018,Cerambyx cerdo,Cerambyx scopolii,Invalid');

        return $file;
    }

    /** @test */
    public function guests_are_not_allowed_to_import_literature_observations()
    {
        $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
                'original_identification', 'original_identification_validity',
            ],
            'file' => $this->validFile(),
            'publication_id' => factory(Publication::class)->create()->id,
        ])->assertUnauthorized();
    }

    /** @test */
    public function regular_users_are_not_allowed_to_import_literature_observations()
    {
        $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
                'original_identification', 'original_identification_validity',
            ],
            'file' => $this->validFile(),
            'publication_id' => factory(Publication::class)->create()->id,
        ])->assertUnauthorized();
    }

    private function actingAsCurator()
    {
        $this->seed('RolesTableSeeder');

        return Passport::actingAs(factory(User::class)->create()->assignRoles('curator'));
    }

    /** @test */
    public function curator_can_submit_csv_file_to_import_literature_observations()
    {
        Queue::fake();
        $this->actingAsCurator();

        $response = $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
                'original_identification', 'original_identification_validity',
            ],
            'file' => $this->validFile(),
            'publication_id' => factory(Publication::class)->create()->id,
            'is_original_data' => true,
        ]);

        $response->assertSuccessful();

        $this->assertArrayHasKey('id', $response->json());
    }

    /** @test */
    public function file_is_required_when_submitting()
    {
        $this->actingAsCurator();

        $response = $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
                'original_identification', 'original_identification_validity',
            ],
            'file' => '',
            'publication_id' => factory(Publication::class)->create()->id,
            'is_original_data' => true,
        ]);

        $response->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function file_must_be_an_actual_file()
    {
        $this->actingAsCurator();

        $response = $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
                'original_identification', 'original_identification_validity',
            ],
            'file' => 'string',
            'publication_id' => factory(Publication::class)->create()->id,
            'is_original_data' => true,
        ]);

        $response->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function submitted_file_must_be_csv()
    {
        $this->actingAsCurator();

        $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
                'original_identification', 'original_identification_validity',
            ],
            'file' => File::fake()->create('import.pdf'),
            'publication_id' => factory(Publication::class)->create()->id,
            'is_original_data' => true,
        ])->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function submitted_file_must_have_at_least_one_row_of_data()
    {
        $this->actingAsCurator();

        $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
                'original_identification', 'original_identification_validity',
            ],
            'file' => File::fake()->create('import.csv'),
            'publication_id' => factory(Publication::class)->create()->id,
            'is_original_data' => true,
        ])->assertJsonValidationErrors(['file']);
    }

    /** @test */
    public function declaring_columns_of_appropriate_order_is_required()
    {
        $this->actingAsCurator();

        $this->postJson('/api/literature-observation-imports', [
            'columns' => [],
            'file' => File::fake()->create('import.csv'),
            'publication_id' => factory(Publication::class)->create()->id,
            'is_original_data' => true,
        ])->assertJsonValidationErrors(['columns']);
    }

    /** @test */
    public function columns_field_must_be_array_of_columns()
    {
        $this->actingAsCurator();

        $this->postJson('/api/literature-observation-imports', [
            'columns' => 'string',
            'file' => File::fake()->create('import.csv'),
            'publication_id' => factory(Publication::class)->create()->id,
            'is_original_data' => true,
        ])->assertJsonValidationErrors(['columns']);
    }

    /** @test */
    public function required_columns_must_be_declared_as_provided_in_the_file()
    {
        $this->actingAsCurator();

        $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
            ],
            'file' => File::fake()->create('import.csv'),
            'publication_id' => factory(Publication::class)->create()->id,
            'is_original_data' => true,
        ])->assertJsonValidationErrors(['columns']);
    }

    /** @test */
    public function publication_is_required_when_submitting_import()
    {
        $this->actingAsCurator();

        $response = $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
                'original_identification', 'original_identification_validity',
            ],
            'file' => $this->validFile(),
            'publication_id' => null,
            'is_original_data' => true,
        ]);

        $response->assertJsonValidationErrors(['publication_id']);
    }

    /** @test */
    public function publication_is_must_exist_when_submitting_import()
    {
        $this->actingAsCurator();

        $response = $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
                'original_identification', 'original_identification_validity',
            ],
            'file' => $this->validFile(),
            'publication_id' => 'invalid',
            'is_original_data' => true,
        ]);

        $response->assertJsonValidationErrors(['publication_id']);
    }

    /** @test */
    public function processing_is_queued_upon_successful_submission()
    {
        Queue::fake();
        $this->actingAsCurator();

        $response = $this->postJson('/api/literature-observation-imports', [
            'columns' => [
                'latitude', 'longitude', 'elevation', 'year', 'taxon',
                'original_identification', 'original_identification_validity',
            ],
            'file' => $this->validFile(),
            'publication_id' => factory(Publication::class)->create()->id,
            'is_original_data' => true,
        ])->assertSuccessful();

        $import = Import::find($response->json('id'));

        Queue::assertPushed(ProcessImport::class, function ($job) use ($import) {
            return $job->import->is($import);
        });
    }

    /** @test */
    public function user_can_check_the_status_to_see_if_processing_started()
    {
        $user = $this->actingAsCurator();

        $import = factory(Import::class)->states([
            'literatureObservation', 'processingQueued',
        ])->create([
            'user_id' => $user->id,
            'path' => $this->validFile()->store('imports'),
        ]);

        $this->getJson("/api/literature-observation-imports/{$import->id}")
            ->assertSuccessful()
            ->assertJson(['status' => ImportStatus::PROCESSING_QUEUED]);
    }

    /** @test */
    public function user_cannot_access_someone_elses_import()
    {
        $this->actingAsCurator();

        $import = factory(Import::class)->states([
            'literatureObservation', 'processingQueued',
        ])->create([
            'user_id' => factory(User::class),
            'path' => $this->validFile()->store('imports'),
        ]);

        $this->getJson("/api/literature-observation-imports/{$import->id}")
            ->assertNotFound();
    }
}
