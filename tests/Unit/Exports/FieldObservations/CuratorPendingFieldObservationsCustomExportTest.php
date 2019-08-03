<?php

namespace Tests\Unit\Exports\FieldObservations;

use App\User;
use App\Stage;
use App\Taxon;
use App\License;
use Tests\TestCase;
use App\Jobs\PerformExport;
use Tests\ObservationFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Box\Spout\Common\Helper\EncodingHelper;
use App\Exports\FieldObservations\CuratorPendingFieldObservationsCustomExport;

class CuratorPendingFieldObservationsCustomExportTest extends TestCase
{
    /** @test */
    public function curated_pending_field_observations_are_exported_to_a_csv_file()
    {
        Carbon::setTestNow(Carbon::now());
        Storage::fake('local');
        $this->seed('StagesTableSeeder');

        $this->actingAs($user = factory(User::class)->create());
        $taxon = factory(Taxon::class)->create(['name' => 'Test taxon']);
        $user->curatedTaxa()->attach($taxon);

        $observation = ObservationFactory::createFieldObservation([
            'created_by_id' => factory(User::class)->create()->id,
            'taxon_id' => $taxon->id,
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
            'approved_at' => null,
            'habitat' => 'Forest',
            'found_on' => 'Ground',
        ], [
            'time' => '10:23',
            'license' => License::CC_BY_SA,
            'found_dead' => true,
            'found_dead_note' => 'Found dead',
        ]);

        $export = CuratorPendingFieldObservationsCustomExport::create([
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
            .'"Test note","Test project",Forest,Ground,Pending'."\n",
            Storage::disk('local')->get($export->path())
        );
    }
}
