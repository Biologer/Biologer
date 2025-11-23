<?php

namespace Tests\Unit\Jobs;

use App\FieldObservation;
use App\Import;
use App\Importing\FieldObservationImport;
use App\Jobs\ProcessImport;
use App\Taxon;
use App\User;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProcessImportTest extends TestCase
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

        $defaultContents = "3,Cerambyx cerdo,Note\n4,Cerambyx scopolii,Other note";

        file_put_contents($file->getPathname(), $contents ?? $defaultContents);

        return $file;
    }

    /** @test */
    public function it_can_perform_processing_of_field_observation_import()
    {
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = User::factory()->create();
        $fieldObservationsCount = FieldObservation::count();

        $import = Import::factory()->create([
            'type' => FieldObservationImport::class,
            'columns' => ['latitude', 'longitude', 'elevation', 'year', 'month', 'day', 'taxon'],
            'path' => $this->validFile('21.123123,42.123123,560,2018,5,22,Cerambyx cerdo')->store('imports'),
            'user_id' => $user->id,
        ]);

        ProcessImport::dispatch($import);

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
        $this->assertEquals($taxon->id, $fieldObservation->observation->taxon_id);
        $this->assertEquals($user->id, $fieldObservation->observation->created_by_id);
    }
}
