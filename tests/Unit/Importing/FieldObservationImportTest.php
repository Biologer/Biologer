<?php

namespace Tests\Unit\Importing;

use App\User;
use App\Taxon;
use App\Import;
use App\License;
use Tests\TestCase;
use App\FieldObservation;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use App\Importing\FieldObservationImport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FieldObservationImportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed('StagesTableSeeder');
    }

    /** @test */
    public function it_can_store_processed_and_validated_import()
    {
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create();
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
        $this->assertEquals('Pine tree', $fieldObservation->observation->found_on);
        $this->assertEquals('adult', $fieldObservation->observation->stage->name);
        $this->assertEquals('custom-dataset', $fieldObservation->observation->dataset);
        $this->assertTrue($fieldObservation->observedBy->is($user));
        $this->assertTrue($fieldObservation->identifiedBy->is($user));
    }

    /** @test */
    public function it_can_store_processed_and_validated_import_including_given_observer_and_identifier_when_admin_is_importing()
    {
        $this->seed('RolesTableSeeder');
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create()->assignRoles('admin');
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
        $this->assertEquals('Pine tree', $fieldObservation->observation->found_on);
        $this->assertEquals('adult', $fieldObservation->observation->stage->name);
        $this->assertEquals('custom-dataset', $fieldObservation->observation->dataset);
        $this->assertNull($fieldObservation->observedBy);
        $this->assertNull($fieldObservation->identifiedBy);
        $this->assertEquals(License::CLOSED, $fieldObservation->license);
    }

    /** @test */
    public function if_original_identification_is_not_given_use_the_taxon_name()
    {
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create();

        $import = $this->createImport(FieldObservationImport::class, [
            'latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon',
        ], '21.123123,42.123123,560,2018,5,22,Cerambyx cerdo', $user);

        // Perform all the steps
        $import->makeImporter()->parse()->validate()->store();

        $fieldObservation = FieldObservation::latest()->first();

        $this->assertEquals('Cerambyx cerdo', $fieldObservation->observation->original_identification);
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
            'male',
            '2',
            'Project name',
            'Pine tree',
            'adult',
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
