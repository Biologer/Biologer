<?php

namespace Tests\Unit\Importing;

use PHPUnit\Framework\Attributes\Test;
use App\DEM\Reader as DEMReader;
use App\FieldObservation;
use App\Import;
use App\Importing\FieldObservationImport;
use App\License;
use App\Taxon;
use App\User;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class FieldObservationImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed('StagesTableSeeder');
    }

    #[Test]
    public function it_can_store_processed_and_validated_import(): void
    {
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = User::factory()->create();
        $fieldObservationsCount = FieldObservation::count();

        $import = $this->createImport(FieldObservationImport::class, $this->allColumns(), $this->defaultContents(), $user);

        // Perform all the steps
        $import->makeImporter()->parse()->validate()->store();

        $this->assertTrue($import->fresh()->status()->saved());
        FieldObservation::assertCount($fieldObservationsCount + 1);
        $fieldObservation = FieldObservation::latest()->first();

        $this->assertEquals(21.1212, $fieldObservation->observation->latitude);
        $this->assertEquals(42.12121, $fieldObservation->observation->longitude);
        $this->assertEquals(350, $fieldObservation->observation->elevation);
        $this->assertEquals(10, $fieldObservation->observation->accuracy);
        $this->assertEquals(2018, $fieldObservation->observation->year);
        $this->assertEquals(5, $fieldObservation->observation->month);
        $this->assertEquals(23, $fieldObservation->observation->day);
        $this->assertEquals('Cerambyx cerdo', $fieldObservation->taxon_suggestion);
        $this->assertEquals('Cerambyx sp.', $fieldObservation->observation->original_identification);
        $this->assertEquals($taxon->id, $fieldObservation->observation->taxon_id);
        $this->assertEquals($user->id, $fieldObservation->observation->created_by_id);
        $this->assertEquals('Novi Sad', $fieldObservation->observation->location);
        $this->assertEquals('10:00', $fieldObservation->time->format('H:i'));
        $this->assertEquals('Some note', $fieldObservation->observation->note);
        $this->assertTrue($fieldObservation->found_dead);
        $this->assertEquals('Death note', $fieldObservation->found_dead_note);
        $this->assertEquals($user->full_name, $fieldObservation->observation->observer);
        $this->assertEquals($user->full_name, $fieldObservation->observation->identifier);
        $this->assertEquals('male', $fieldObservation->observation->sex);
        $this->assertEquals(2, $fieldObservation->observation->number);
        $this->assertEquals('Project name', $fieldObservation->observation->project);
        $this->assertEquals('Mountain', $fieldObservation->observation->habitat);
        $this->assertEquals('Pine tree', $fieldObservation->observation->found_on);
        $this->assertEquals('adult', $fieldObservation->observation->stage->name);
        $this->assertEquals('custom-dataset', $fieldObservation->observation->dataset);
        $this->assertTrue($fieldObservation->observedBy->is($user));
        $this->assertTrue($fieldObservation->identifiedBy->is($user));
    }

    #[Test]
    public function it_can_store_processed_and_validated_import_including_given_observer_and_identifier_when_admin_is_importing(): void
    {
        $this->seed('RolesTableSeeder');
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = User::factory()->create()->assignRoles('admin');
        $fieldObservationsCount = FieldObservation::count();

        $import = $this->createImport(FieldObservationImport::class, $this->allColumns(), $this->defaultContents(), $user);

        // Perform all the steps
        $import->makeImporter()->parse()->validate()->store();

        $this->assertTrue($import->fresh()->status()->saved());
        FieldObservation::assertCount($fieldObservationsCount + 1);
        $fieldObservation = FieldObservation::latest()->first();

        $this->assertEquals(21.1212, $fieldObservation->observation->latitude);
        $this->assertEquals(42.12121, $fieldObservation->observation->longitude);
        $this->assertEquals(350, $fieldObservation->observation->elevation);
        $this->assertEquals(10, $fieldObservation->observation->accuracy);
        $this->assertEquals(2018, $fieldObservation->observation->year);
        $this->assertEquals(5, $fieldObservation->observation->month);
        $this->assertEquals(23, $fieldObservation->observation->day);
        $this->assertEquals('Cerambyx cerdo', $fieldObservation->taxon_suggestion);
        $this->assertEquals('Cerambyx sp.', $fieldObservation->observation->original_identification);
        $this->assertEquals($taxon->id, $fieldObservation->observation->taxon_id);
        $this->assertEquals($user->id, $fieldObservation->observation->created_by_id);
        $this->assertEquals('Novi Sad', $fieldObservation->observation->location);
        $this->assertEquals('10:00', $fieldObservation->time->format('H:i'));
        $this->assertEquals('Some note', $fieldObservation->observation->note);
        $this->assertTrue($fieldObservation->found_dead);
        $this->assertEquals('Death note', $fieldObservation->found_dead_note);
        $this->assertEquals('Observer name', $fieldObservation->observation->observer);
        $this->assertEquals('Identifier name', $fieldObservation->observation->identifier);
        $this->assertEquals('male', $fieldObservation->observation->sex);
        $this->assertEquals(2, $fieldObservation->observation->number);
        $this->assertEquals('Project name', $fieldObservation->observation->project);
        $this->assertEquals('Mountain', $fieldObservation->observation->habitat);
        $this->assertEquals('Pine tree', $fieldObservation->observation->found_on);
        $this->assertEquals('adult', $fieldObservation->observation->stage->name);
        $this->assertEquals('custom-dataset', $fieldObservation->observation->dataset);
        $this->assertNull($fieldObservation->observedBy);
        $this->assertNull($fieldObservation->identifiedBy);
        $this->assertEquals(License::CLOSED, $fieldObservation->license);
    }

    #[Test]
    public function if_original_identification_is_not_given_use_the_taxon_name(): void
    {
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = User::factory()->create();

        $import = $this->createImport(FieldObservationImport::class, [
            'latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon',
        ], '21.123123,42.123123,560,2018,5,22,Cerambyx cerdo', $user);

        // Perform all the steps
        $import->makeImporter()->parse()->validate()->store();

        $fieldObservation = FieldObservation::latest()->first();

        $this->assertEquals('Cerambyx cerdo', $fieldObservation->observation->original_identification);
    }

    #[Test]
    public function if_elevation_is_missing_try_using_dem_reader_to_get_elevation(): void
    {
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = User::factory()->create();

        $import = $this->createImport(FieldObservationImport::class, [
            'latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon',
        ], '21.123123,42.123123,,2018,5,22,Cerambyx cerdo', $user);

        $fakeDEMReader = new class implements DEMReader {
            public function getElevation($latitude, $longitude)
            {
                return 300;
            }
        };

        // Perform all the steps
        $import->makeImporter()->setDEMReader($fakeDEMReader)->parse()->validate()->store();

        $fieldObservation = FieldObservation::latest()->first();

        $this->assertEquals(300, $fieldObservation->observation->elevation);
    }

    #[Test]
    public function if_submitted_by_curator_observations_of_taxa_they_curate_can_be_approved(): void
    {
        $this->seed('RolesTableSeeder');
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = User::factory()->create()->assignRoles('curator');
        $user->curatedTaxa()->attach($taxon);
        $fieldObservationsCount = FieldObservation::count();

        $import = $this->createImport(FieldObservationImport::class, $this->allColumns(), $this->defaultContents(), $user);
        $import->update(['options' => collect(['approve_curated' => true])]);

        // Perform all the steps
        $import->makeImporter()->parse()->validate()->store();

        $this->assertTrue($import->fresh()->status()->saved());
        FieldObservation::assertCount($fieldObservationsCount + 1);
        $fieldObservation = FieldObservation::latest()->first();
        $this->assertTrue($fieldObservation->isApproved());
        $this->assertEquals('approved', $fieldObservation->activity->first()->description);
        $this->assertTrue($fieldObservation->activity->first()->causer->is($user));
    }

    #[Test]
    public function if_submitted_by_curator_observations_of_taxa_they_dont_curate_cannot_be_approved(): void
    {
        $this->seed('RolesTableSeeder');
        Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = User::factory()->create()->assignRoles('curator');
        $fieldObservationsCount = FieldObservation::count();

        $import = $this->createImport(FieldObservationImport::class, $this->allColumns(), $this->defaultContents(), $user);
        $import->update(['options' => collect(['approve_curated' => true])]);

        // Perform all the steps
        $import->makeImporter()->parse()->validate()->store();

        $this->assertTrue($import->fresh()->status()->saved());
        FieldObservation::assertCount($fieldObservationsCount + 1);
        $fieldObservation = FieldObservation::latest()->first();
        $this->assertFalse($fieldObservation->isApproved());
        $this->assertEquals('created', $fieldObservation->activity->first()->description);
    }

    #[Test]
    public function cannot_be_approved_if_submitted_by_non_curators(): void
    {
        Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = User::factory()->create();
        $fieldObservationsCount = FieldObservation::count();

        $import = $this->createImport(FieldObservationImport::class, $this->allColumns(), $this->defaultContents(), $user);
        $import->update(['options' => collect(['approve_curated' => true])]);

        // Perform all the steps
        $import->makeImporter()->parse()->validate()->store();

        $this->assertTrue($import->fresh()->status()->saved());
        FieldObservation::assertCount($fieldObservationsCount + 1);
        $fieldObservation = FieldObservation::latest()->first();
        $this->assertFalse($fieldObservation->isApproved());
    }

    /**
     * All columns to import.
     *
     * @return array
     */
    private function allColumns()
    {
        return [
            'taxon',
            'year',
            'month',
            'day',
            'latitude',
            'longitude',
            'elevation',
            'accuracy',
            'location',
            'time',
            'note',
            'found_dead',
            'found_dead_note',
            'observer',
            'identifier',
            'sex',
            'number',
            'project',
            'habitat',
            'found_on',
            'stage',
            'original_identification',
            'dataset',
            'license',
        ];
    }

    /**
     * Default content with values for all columns.
     *
     * @return string
     */
    private function defaultContents()
    {
        return implode(',', [
            'Cerambyx cerdo',
            '2018',
            '5',
            '23',
            '21.1212',
            '42.12121',
            '350',
            '10',
            'Novi Sad',
            '10:00',
            'Some note',
            'Yes',
            'Death note',
            'Observer name',
            'Identifier name',
            'Male',
            '2',
            'Project name',
            'Mountain',
            'Pine tree',
            'Adult',
            'Cerambyx sp.',
            'custom-dataset',
            'Closed',
        ]);
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

        file_put_contents($file->getPathname(), $contents ?? $this->defaultContents());

        return $file;
    }

    /**
     * Create instance of import.
     *
     * @param  string  $type
     * @param  array  $columns  [description]
     * @param  string $contents [description]
     * @param  \App\User|null  $user
     * @return \App\Import
     */
    protected function createImport($type, array $columns = [], $contents = null, $user = null)
    {
        return Import::create([
            'type' => $type,
            'columns' => $columns,
            'path' => $this->validFile($contents)->store('imports'),
            'user_id' => $user ? $user->id : 1,
            'lang' => app()->getLocale(),
        ]);
    }
}
