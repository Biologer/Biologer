<?php

namespace Tests\Unit\Exports\LiteratureObservations;

use App\Exports\LiteratureObservations\CustomLiteratureObservationsExport;
use App\Jobs\PerformExport;
use App\LiteratureObservation;
use App\LiteratureObservationIdentificationValidity;
use App\Observation;
use App\Publication;
use App\Taxon;
use App\User;
use Box\Spout\Common\Helper\EncodingHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CustomLiteratureObservationsExportTest extends TestCase
{
    #[Test]
    public function all_literature_observations_are_exported_to_a_csv_file(): void
    {
        Carbon::setTestNow(Carbon::now());
        Storage::fake('local');
        $this->seed('StagesTableSeeder');

        $this->actingAs(User::factory()->create());

        $observation = $this->createLiteratureObservation();

        $export = CustomLiteratureObservationsExport::create([
            'id', 'taxon', 'year', 'month', 'day', 'elevation', 'minimum_elevation',
            'maximum_elevation', 'latitude', 'longitude', 'mgrs10k', 'location',
            'accuracy', 'georeferenced_by', 'georeferenced_date', 'observer',
            'identifier', 'note', 'sex', 'number', 'project', 'found_on', 'habitat',
            'stage', 'time', 'dataset', 'publication', 'is_original_data',
            'cited_publication', 'place_where_referenced_in_publication',
            'original_date', 'original_locality', 'original_elevation', 'original_coordinates',
            'original_identification', 'original_identification_validity',
        ], [], true);

        (new PerformExport($export))->handle();

        Storage::disk('local')->assertExists($export->path());

        $this->assertEquals(
            EncodingHelper::BOM_UTF8
            .'ID,Taxon,Year,Month,Day,"Elevation (m)","Minimum Elevation (m)",'
            .'"Maximum Elevation (m)",Latitude,Longitude,"MGRS 10k",Location,"Accuracy (m)",'
            .'"Georeferenced By","Georeferenced on Date",Observer,Identifier,Note,Sex,'
            .'Number,Project,"Found On",Habitat,Stage,Time,Dataset,Publication,'
            .'"Is Original Data?","Cited Publication","Place of Reference in Publication",'
            .'"Original Date","Original Locality","Original Elevation","Original Coordinates",'
            .'"Original Identification","Original Identification Validity"'."\n"
            .$observation->id.',"Test taxon",1990,5,12,370,350,400,21.123123,'
            .'43.123123,38QMJ43,"Gledić Mountains",10,"Pera Detlić",2019-05-20,'
            .'"Test observer","Test identifier",,,,,,,,,,"Test citation",'
            .'Yes,,,"May 12 1990","Gledić Mountains",300-500m,"20°22\'44"",43°21\'35""",'
            .'"Testudo hermanii",Valid'."\n",
            Storage::disk('local')->get($export->path())
        );
    }

    protected function createLiteratureObservation()
    {
        $literatureObservation = LiteratureObservation::factory()->create([
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification_validity' => LiteratureObservationIdentificationValidity::VALID,
            'publication_id' => Publication::factory()->create(['citation' => 'Test citation'])->id,
            'is_original_data' => true,
            'cited_publication_id' => null,
            'minimum_elevation' => 350,
            'maximum_elevation' => 400,
            'georeferenced_by' => 'Pera Detlić',
            'georeferenced_date' => Carbon::parse('2019-05-20'),
        ]);

        $literatureObservation->observation()->save(Observation::factory()->make([
            'original_identification' => 'Testudo hermanii',
            'taxon_id' => Taxon::factory()->create(['name' => 'Test taxon'])->id,
            'year' => 1990,
            'month' => 5,
            'day' => 12,
            'latitude' => 21.123123,
            'longitude' => 43.123123,
            'location' => 'Gledić Mountains',
            'accuracy' => 10,
            'elevation' => 370,
            'created_by_id' => User::factory()->create()->id,
            'observer' => 'Test observer',
            'identifier' => 'Test identifier',
        ]));

        return $literatureObservation;
    }
}
