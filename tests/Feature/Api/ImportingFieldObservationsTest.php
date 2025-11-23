<?php

namespace Tests\Feature\Api;

use App\Models\Import;
use App\Importing\ImportStatus;
use App\Jobs\ProcessImport;
use App\Models\User;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ImportingFieldObservationsTest extends TestCase
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

        file_put_contents($file->getPathname(), $contents ?? '21.1212,42.12121,350,2018,5,23,Cerambyx cerdo,"CC BY-SA 4.0"');

        return $file;
    }

    #[Test]
    public function guests_are_not_allowed_to_import_observations()
    {
        $this->postJson('/api/field-observation-imports', [
            'columns' => ['latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon'],
            'file' => $this->validFile(),
        ])->assertUnauthorized();
    }

    #[Test]
    public function authenticated_user_can_submit_csv_file_to_import_fied_observations()
    {
        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/field-observation-imports', [
            'columns' => ['latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon', 'license'],
            'file' => $this->validFile(),
        ]);

        $response->assertSuccessful();

        $this->assertArrayHasKey('id', $response->json());
    }

    #[Test]
    public function file_is_required_when_submitting()
    {
        Passport::actingAs(User::factory()->make());

        $response = $this->postJson('/api/field-observation-imports', [
            'columns' => ['latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon'],
            'file' => '',
        ]);

        $response->assertJsonValidationErrors(['file']);
    }

    #[Test]
    public function file_must_be_an_actual_file()
    {
        Passport::actingAs(User::factory()->make());

        $response = $this->postJson('/api/field-observation-imports', [
            'columns' => ['latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon'],
            'file' => 'string',
        ]);

        $response->assertJsonValidationErrors(['file']);
    }

    #[Test]
    public function submitted_file_must_be_csv()
    {
        Passport::actingAs(User::factory()->make());

        $this->postJson('/api/field-observation-imports', [
            'columns' => ['latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon'],
            'file' => File::fake()->create('import.pdf'),
        ])->assertJsonValidationErrors(['file']);
    }

    #[Test]
    public function submitted_file_must_have_at_least_one_row_of_data()
    {
        Passport::actingAs(User::factory()->make());

        $this->postJson('/api/field-observation-imports', [
            'columns' => ['longitude', 'year', 'elevation', 'month', 'day', 'taxon'],
            'file' => File::fake()->create('import.csv'),
        ])->assertJsonValidationErrors(['file']);
    }

    #[Test]
    public function declaring_columns_of_appropriate_order_is_required()
    {
        Passport::actingAs(User::factory()->make());

        $this->postJson('/api/field-observation-imports', [
            'columns' => [],
            'file' => File::fake()->create('import.csv'),
        ])->assertJsonValidationErrors(['columns']);
    }

    #[Test]
    public function columns_field_must_be_array_of_columns()
    {
        Passport::actingAs(User::factory()->make());

        $this->postJson('/api/field-observation-imports', [
            'columns' => 'string',
            'file' => File::fake()->create('import.csv'),
        ])->assertJsonValidationErrors(['columns']);
    }

    #[Test]
    public function required_columns_must_be_declared_as_provided_in_the_file()
    {
        Passport::actingAs(User::factory()->make());

        $this->postJson('/api/field-observation-imports', [
            'columns' => ['longitude', 'year', 'month', 'day', 'taxon'],
            'file' => File::fake()->create('import.csv'),
        ])->assertJsonValidationErrors(['columns']);
    }

    #[Test]
    public function processing_is_queued_upon_successful_submition()
    {
        Queue::fake();
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/field-observation-imports', [
            'columns' => ['latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon', 'license'],
            'file' => $this->validFile(),
        ])->assertSuccessful();

        $import = Import::find($response->json('id'));

        Queue::assertPushed(ProcessImport::class, function ($job) use ($import) {
            return $job->import->is($import);
        });
    }

    #[Test]
    public function user_can_check_the_status_to_see_if_processing_started()
    {
        Passport::actingAs($user = User::factory()->create());

        $import = Import::factory()->fieldObservation()->processingQueued()->create([
            'user_id' => $user->id,
            'path' => $this->validFile()->store('imports'),
        ]);

        $this->getJson("/api/field-observation-imports/{$import->id}")
            ->assertSuccessful()
            ->assertJson(['status' => ImportStatus::PROCESSING_QUEUED]);
    }

    #[Test]
    public function user_cannot_access_someone_elses_import()
    {
        Passport::actingAs(User::factory()->create());

        $import = Import::factory()->fieldObservation()->processingQueued()->create([
            'user_id' => User::factory(),
            'path' => $this->validFile()->store('imports'),
        ]);

        $this->getJson("/api/field-observation-imports/{$import->id}")
            ->assertNotFound();
    }

    #[Test]
    #[DataProvider('roles')]
    public function admins_and_curators_can_submit_data_to_be_imported_for_someone_else($role)
    {
        Queue::fake();
        $this->seed('RolesTableSeeder');

        $anotherUser = User::factory()->create();

        Passport::actingAs(User::factory()->create()->assignRoles($role));

        $response = $this->postJson('/api/field-observation-imports', [
            'columns' => ['latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon', 'license'],
            'file' => $this->validFile(),
            'user_id' => $anotherUser->id,
        ])->assertSuccessful();

        $import = Import::find($response->json('id'));

        $this->assertEquals($anotherUser->id, $import->for_user_id);
        Queue::assertPushed(ProcessImport::class, function ($job) use ($import) {
            return $job->import->is($import);
        });
    }

    public static function roles()
    {
        return [
            ['admin'],
            ['curator'],
        ];
    }

    #[Test]
    public function user_that_should_be_owner_must_exist()
    {
        Queue::fake();

        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $this->postJson('/api/field-observation-imports', [
            'columns' => ['latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon', 'license'],
            'file' => $this->validFile(),
            'user_id' => 99999999,
        ])->assertJsonValidationErrors('user_id');
    }

    #[Test]
    public function contributor_cannot_sumbit_import_for_someone_else()
    {
        Queue::fake();

        $anotherUser = User::factory()->create();

        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/field-observation-imports', [
            'columns' => ['latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon', 'license'],
            'file' => $this->validFile(),
            'user_id' => $anotherUser->id,
        ])->assertSuccessful();

        $import = Import::find($response->json('id'));

        $this->assertNull($import->for_user_id);
        Queue::assertPushed(ProcessImport::class, function ($job) use ($import) {
            return $job->import->is($import);
        });
    }
}
