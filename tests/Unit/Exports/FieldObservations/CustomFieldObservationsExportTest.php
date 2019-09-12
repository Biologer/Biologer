<?php

namespace Tests\Unit\Exports\FieldObservations;

use App\Exports\FieldObservations\CustomFieldObservationsExport;
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

class CustomFieldObservationsExportTest extends TestCase
{
    /** @test */
    public function all_field_observations_are_exported_to_a_csv_file()
    {
        Carbon::setTestNow(Carbon::now());
        Storage::fake('local');
        $this->seed('StagesTableSeeder');

        $this->actingAs(factory(User::class)->create());

        $observation = ObservationFactory::createFieldObservation([
            'created_by_id' => factory(User::class)->create(),
            'taxon_id' => factory(Taxon::class)->create(['name' => 'Test taxon']),
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

        $export = CustomFieldObservationsExport::create([
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

    /** @test */
    public function all_columns_are_available_for_export_to_curators_and_admins()
    {
        $this->seed('RolesTableSeeder');
        $user = factory(User::class)->create()->assignRoles('admin');

        $this->actingAs($user);

        $this->assertEquals([
            'id', 'taxon', 'year', 'month', 'day', 'time', 'latitude', 'longitude',
            'location', 'mgrs10k', 'accuracy', 'elevation', 'sex', 'observer',
            'identifier', 'stage', 'license', 'number', 'note', 'project',
            'habitat', 'found_on', 'found_dead', 'found_dead_note', 'status', 'types',
        ], CustomFieldObservationsExport::availableColumns()->all());
    }
}
