<?php

namespace Tests\Unit;

use App\User;
use App\Taxon;
use App\Import;
use Tests\TestCase;
use App\FieldObservation;
use App\Importing\BaseImport;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use App\Importing\FieldObservationImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ImportingTest extends TestCase
{
    use RefreshDatabase;

    protected $importer;

    protected function setUp()
    {
        parent::setUp();

        Storage::fake('local');
        Storage::fake('public');

        $this->importer = new class extends BaseImport {
            public function allColumns()
            {
                return [
                    ['value' => 'a', 'label' => 'A', 'required' => false],
                    ['value' => 'b', 'label' => 'B', 'required' => true],
                    ['value' => 'c', 'label' => 'C', 'required' => false],
                ];
            }

            protected function makeValidator($data)
            {
                return Validator::make($data, [
                    'a' => 'nullable|numeric|min:2',
                    'b' => 'required|string',
                    'c' => 'nullable|string',
                ]);
            }

            protected function storeSingleItem($import, $item)
            {
                //
            }
        };
    }

    protected function tearDown()
    {
        parent::tearDown();

        unset($this->importer);
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

        $defaultContents = "3,Cerambyx cerdo,Note\n4,Cerambyx scopolii,Other note";

        file_put_contents($file->getPathname(), $contents ?? $defaultContents);

        return $file;
    }

    protected function createImport(array $columns = [], $contents = null, $user = null)
    {
        return Import::create([
            'type' => FieldObservationImport::class,
            'columns' => $columns,
            'path' => $this->validFile($contents)->store('imports'),
            'user_id' => $user ? $user->id : 1,
            'lang' => app()->getLocale(),
        ]);
    }

    /** @test */
    public function it_can_parse_csv_file_and_map_the_columns()
    {
        $import = $this->createImport(['a', 'b', 'c']);

        $this->importer->parse($import);

        $this->assertTrue($import->status()->parsed());
        Storage::disk('public')->assertExists($import->parsedPath());

        $contents = Storage::disk('public')->get($import->parsedPath());
        $this->assertEquals([
            [
                'a' => '3',
                'b' => 'Cerambyx cerdo',
                'c' => 'Note',
            ],
            [
                'a' => '4',
                'b' => 'Cerambyx scopolii',
                'c' => 'Other note',
            ],
        ], json_decode($contents, true));
    }

    /** @test */
    public function it_can_validate_processed_import_and_pass_if_there_are_not_errors()
    {
        $import = $this->createImport(['a', 'b', 'c']);

        $this->importer->validate($this->importer->parse($import));

        $this->assertTrue($import->status()->validationPassed());
    }

    /** @test */
    public function it_can_validate_processed_import_and_fail_if_there_are_errors()
    {
        $content = "1,Cerambix cerdo,Note\n2,,Other note\n1,,LastNote";
        $import = $this->createImport(['a', 'b', 'c'], $content);

        $this->importer->validate($this->importer->parse($import));

        $this->assertTrue($import->status()->validationFailed());
        Storage::disk('public')->assertExists($import->errorsPath());
        $contents = Storage::disk('public')->get($import->errorsPath());

        $expectedRowNumbers = [1, 2, 3, 3];
        $expectedRowColumns = ['a', 'b', 'a', 'b'];

        foreach (json_decode($contents, true) as $i => $row) {
            $this->assertArrayHasKey('row', $row);
            $this->assertEquals($expectedRowNumbers[$i], $row['row']);
            $this->assertArrayHasKey('error', $row);
            $this->assertContains($expectedRowColumns[$i], $row['error']);
        }
    }

    /** @test */
    public function it_can_store_processed_and_validated_import()
    {
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create();
        $fieldObservationsCount = FieldObservation::count();

        $import = $this->createImport([
            'latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon', 'original_identification',
        ], '21.123123,42.123123,560,2018,5,22,Cerambyx cerdo,Cerambyx sp.', $user);

        // Perform all the steps
        $importer = app(FieldObservationImport::class);
        $importer->parse($import);
        $importer->validate($import);
        $importer->store($import);

        $this->assertTrue($import->fresh()->status()->saved());
        FieldObservation::assertCount($fieldObservationsCount + 1);
        $fieldObservation = FieldObservation::latest()->first();

        $this->assertEquals(21.123123, $fieldObservation->observation->latitude);
        $this->assertEquals(42.123123, $fieldObservation->observation->longitude);
        $this->assertEquals(560, $fieldObservation->observation->elevation);
        $this->assertEquals(2018, $fieldObservation->observation->year);
        $this->assertEquals(5, $fieldObservation->observation->month);
        $this->assertEquals(22, $fieldObservation->observation->day);
        $this->assertEquals('Cerambyx cerdo', $fieldObservation->taxon_suggestion);
        $this->assertEquals('Cerambyx sp.', $fieldObservation->observation->original_identification);
        $this->assertEquals($taxon->id, $fieldObservation->observation->taxon_id);
        $this->assertEquals($user->id, $fieldObservation->observation->created_by_id);
    }

    /** @test */
    public function if_original_identification_is_not_given_use_the_taxon_name()
    {
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create();

        $import = $this->createImport([
            'latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon',
        ], '21.123123,42.123123,560,2018,5,22,Cerambyx cerdo', $user);

        // Perform all the steps
        $importer = app(FieldObservationImport::class);
        $importer->parse($import);
        $importer->validate($import);
        $importer->store($import);

        $fieldObservation = FieldObservation::latest()->first();

        $this->assertEquals('Cerambyx cerdo', $fieldObservation->observation->original_identification);
    }
}
