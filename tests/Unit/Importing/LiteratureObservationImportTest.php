<?php

namespace Tests\Unit\Importing;

use App\DEM\Reader as DEMReader;
use App\LiteratureObservation;
use App\Import;
use App\Importing\LiteratureObservationImport;
use App\LiteratureObservationIdentificationValidity;
use App\Publication;
use App\Taxon;
use App\User;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LiteratureObservationImportTest extends TestCase
{
    protected function setUp(): void
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
        $literatureObservationsCount = LiteratureObservation::count();

        $import = $this->createImport(LiteratureObservationImport::class, $this->allColumns(), $this->defaultContents(), $user);

        // Perform all the steps
        $import->makeImporter()->parse()->validate()->store();

        $this->assertTrue($import->fresh()->status()->saved());
        LiteratureObservation::assertCount($literatureObservationsCount + 1);
        $literatureObservation = LiteratureObservation::latest()->first();

        $this->assertEquals(21.1212, $literatureObservation->observation->latitude);
        $this->assertEquals(42.12121, $literatureObservation->observation->longitude);
        $this->assertEquals(350, $literatureObservation->observation->elevation);
        $this->assertEquals(10, $literatureObservation->observation->accuracy);
        $this->assertEquals(2018, $literatureObservation->observation->year);
        $this->assertEquals(5, $literatureObservation->observation->month);
        $this->assertEquals(23, $literatureObservation->observation->day);
        $this->assertEquals('Cerambyx sp.', $literatureObservation->observation->original_identification);
        $this->assertEquals(LiteratureObservationIdentificationValidity::INVALID, $literatureObservation->original_identification_validity);
        $this->assertEquals($taxon->id, $literatureObservation->observation->taxon_id);
        $this->assertEquals($user->id, $literatureObservation->observation->created_by_id);
        $this->assertEquals('Novi Sad', $literatureObservation->observation->location);
        $this->assertEquals('10:00', $literatureObservation->time->format('H:i'));
        $this->assertEquals('Some note', $literatureObservation->observation->note);
        $this->assertEquals('Observer name', $literatureObservation->observation->observer);
        $this->assertEquals('Identifier name', $literatureObservation->observation->identifier);
        $this->assertEquals('male', $literatureObservation->observation->sex);
        $this->assertEquals(2, $literatureObservation->observation->number);
        $this->assertEquals('Project name', $literatureObservation->observation->project);
        $this->assertEquals('Mountain', $literatureObservation->observation->habitat);
        $this->assertEquals('Pine tree', $literatureObservation->observation->found_on);
        $this->assertEquals('adult', $literatureObservation->observation->stage->name);
        $this->assertEquals('custom-dataset', $literatureObservation->observation->dataset);
        $this->assertEquals('200', $literatureObservation->minimum_elevation);
        $this->assertEquals('400', $literatureObservation->maximum_elevation);
        $this->assertEquals('May 23, 2018', $literatureObservation->original_date);
        $this->assertEquals('Mountain', $literatureObservation->original_locality);
        $this->assertEquals('200-400m', $literatureObservation->original_elevation);
        $this->assertEquals('21.21312,43.123123', $literatureObservation->original_coordinates);
        $this->assertEquals('Georeferencer', $literatureObservation->georeferenced_by);
        $this->assertEquals('2019-01-01', $literatureObservation->georeferenced_date->toDateString());
        $this->assertEquals('Table 1', $literatureObservation->place_where_referenced_in_publication);
        $this->assertEquals('Some more information', $literatureObservation->other_original_data);
    }


    /** @test */
    public function if_elevation_is_missing_try_using_dem_reader_to_get_elevation()
    {
        factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create();

        $import = $this->createImport(LiteratureObservationImport::class, [
            'latitude', 'longitude', 'elevation', 'year', 'taxon',
            'original_identification', 'original_identification_validity',
        ], '21.123123,42.123123,,2018,Cerambyx cerdo,Cerambyx sp.,Invalid', $user);

        $fakeDEMReader = new class implements DEMReader {
            public function getElevation($latitude, $longitude)
            {
                return 300;
            }
        };

        // Perform all the steps
        $import->makeImporter()->setDEMReader($fakeDEMReader)->parse()->validate()->store();

        $literatureObservation = LiteratureObservation::latest()->first();

        $this->assertEquals(300, $literatureObservation->observation->elevation);
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
            'observer',
            'identifier',
            'sex',
            'number',
            'project',
            'habitat',
            'found_on',
            'stage',
            'dataset',
            'original_identification',
            'original_identification_validity',
            'other_original_data',
            'minimum_elevation',
            'maximum_elevation',
            'original_date',
            'original_locality',
            'original_elevation',
            'original_coordinates',
            'georeferenced_by',
            'georeferenced_date',
            'place_where_referenced_in_publication',
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
            'Observer name',
            'Identifier name',
            'Male',
            '2',
            'Project name',
            'Mountain',
            'Pine tree',
            'Adult',
            'custom-dataset',
            'Cerambyx sp.',
            'Invalid',
            'Some more information',
            '200',
            '400',
            '"May 23, 2018"',
            'Mountain',
            '200-400m',
            '"21.21312,43.123123"',
            'Georeferencer',
            '2019-01-01',
            'Table 1',
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
            'options' => [
                'publication_id' => factory(Publication::class)->create()->id,
                'is_original_data' => true,
                'cited_publication_id' => null,
            ],
        ]);
    }

    /** @test */
    public function georeferenced_date_is_normalized_before_storing_it()
    {
        factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create();

        $import = $this->createImport(LiteratureObservationImport::class, [
            'latitude', 'longitude', 'elevation', 'year', 'taxon',
            'original_identification', 'original_identification_validity',
            'georeferenced_date',
        ], '21.123123,42.123123,30,2018,Cerambyx cerdo,Cerambyx sp.,Invalid,12.06.2010.', $user);

        // Perform all the steps
        $import->makeImporter()->parse()->validate()->store();

        $literatureObservation = LiteratureObservation::latest()->first();

        $this->assertEquals('2010-06-12', $literatureObservation->georeferenced_date->toDateString());
    }
}
