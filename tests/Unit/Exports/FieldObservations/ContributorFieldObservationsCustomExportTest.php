<?php

namespace Tests\Unit\Exports\FieldObservations;

use App\Exports\FieldObservations\ContributorFieldObservationsCustomExport;
use App\Jobs\PerformExport;
use App\License;
use App\Stage;
use App\Taxon;
use App\User;
use Box\Spout\Common\Helper\EncodingHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\ObservationFactory;
use Tests\TestCase;

class ContributorFieldObservationsCustomExportTest extends TestCase
{
    /** @test */
    public function contributors_field_observations_are_exported_to_a_csv_file()
    {
        Carbon::setTestNow(Carbon::now());
        Storage::fake('local');
        $this->seed('StagesTableSeeder');

        $this->actingAs($user = User::factory()->create());

        $observation = ObservationFactory::createFieldObservation([
            'created_by_id' => $user,
            'taxon_id' => Taxon::factory()->create(['name' => 'Test taxon']),
            'year' => 2001,
            'month' => 2,
            'day' => 23,
            'latitude' => 12.3456,
            'longitude' => 12.3456,
            'location' => 'Test location',
            'mgrs10k' => '34TEST34',
            'accuracy' => 12,
            'elevation' => 123,
            'sex' => 'male',
            'observer' => 'Test observer',
            'identifier' => 'Test identifier',
            'stage_id' => Stage::where('name', 'larva')->first(),
            'number' => 2,
            'note' => 'Test note',
            'project' => 'Test project',
            'approved_at' => now(),
            'habitat' => 'Forest',
            'found_on' => 'Ground',
        ], [
            'time' => '10:23',
            'license' => License::CC_BY_SA,
            'found_dead' => true,
            'found_dead_note' => 'Found dead',
        ]);

        $export = ContributorFieldObservationsCustomExport::create([
            'id', 'taxon', 'identifier', 'observer', 'sex', 'year', 'month',
            'day', 'latitude', 'longitude', 'location', 'accuracy', 'elevation',
            'stage', 'number', 'note', 'project', 'habitat', 'found_on', 'status',
        ], [], true);

        (new PerformExport($export))->handle();

        Storage::disk('local')->assertExists($export->path());

        $this->assertEquals(
            EncodingHelper::BOM_UTF8
            .'ID,Taxon,Identifier,Observer,Sex,Year,Month,Day,Latitude,Longitude,'
            .'Location,Accuracy,Elevation,Stage,Number,Note,Project,Habitat,"Found On",Status'."\n"
            .$observation->id.',"Test taxon","Test identifier","Test observer",'
            .'Male,2001,2,23,12.3456,12.3456,"Test location",12,123,Larva,2,'
            .'"Test note","Test project",Forest,Ground,Approved'."\n",
            Storage::disk('local')->get($export->path())
        );
    }
}
