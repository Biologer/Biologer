<?php

namespace Tests\Unit\Exports\FieldObservations;

use PHPUnit\Framework\Attributes\Test;
use App\Exports\FieldObservations\ContributorFieldObservationsDarwinCoreExport;
use App\Jobs\PerformExport;
use App\License;
use App\ObservationType;
use App\Stage;
use App\Taxon;
use App\User;
use Box\Spout\Common\Helper\EncodingHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\ObservationFactory;
use Tests\TestCase;

class ContributorFieldObservationsDarwinCoreExportTest extends TestCase
{
    #[Test]
    public function contributors_field_observations_are_exported_in_darwin_core_to_a_csv_file(): void
    {
        Carbon::setTestNow(Carbon::now());
        Storage::fake('local');
        $this->seed('StagesTableSeeder');
        $this->seed('ObservationTypesTableSeeder');

        $this->actingAs($user = User::factory()->create());

        $taxon = $this->createTaxon();

        $observation = ObservationFactory::createFieldObservation([
            'created_by_id' => $user->id,
            'taxon_id' => $taxon->id,
            'year' => 2001,
            'month' => 2,
            'day' => 23,
            'latitude' => 12.3456,
            'longitude' => 15.3456,
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
            'found_on' => 'Ground',
        ], [
            'time' => '10:23',
            'license' => License::CC_BY_SA,
            'found_dead' => true,
            'found_dead_note' => 'Found dead',
        ]);

        $type = ObservationType::whereSlug('observed')->first();
        $observation->observation->types()->attach($type);

        $export = ContributorFieldObservationsDarwinCoreExport::createFiltered();

        (new PerformExport($export))->handle();

        Storage::disk('local')->assertExists($export->path());

        $expectedKeyValuePairs = collect($this->csvContent($observation));

        $this->assertEquals(
            EncodingHelper::BOM_UTF8.
            $expectedKeyValuePairs->keys()->implode(',')."\n".
            $expectedKeyValuePairs->implode(',')."\n",
            Storage::disk('local')->get($export->path())
        );
    }

    private function createTaxon()
    {
        $kingdom = Taxon::factory()->create([
            'name' => 'Animalia',
            'rank' => 'kingdom',
        ]);

        $phylum = Taxon::factory()->create([
            'parent_id' => $kingdom->id,
            'name' => 'Arthropoda',
            'rank' => 'phylum',
        ]);

        $class = Taxon::factory()->create([
            'parent_id' => $phylum->id,
            'name' => 'Insecta',
            'rank' => 'class',
        ]);

        $order = Taxon::factory()->create([
            'parent_id' => $class->id,
            'name' => 'Coleoptera',
            'rank' => 'order',
        ]);

        $family = Taxon::factory()->create([
            'parent_id' => $order->id,
            'name' => 'Cerambycidae',
            'rank' => 'family',
        ]);

        $genus = Taxon::factory()->create([
            'parent_id' => $family->id,
            'name' => 'Cerambyx',
            'rank' => 'genus',
        ]);

        $species = Taxon::factory()->create([
            'parent_id' => $genus->id,
            'name' => 'Cerambyx cerdo',
            'rank' => 'species',
        ]);

        return Taxon::factory()->create([
            'parent_id' => $species->id,
            'name' => 'Cerambyx cerdo cerdo',
            'author' => 'Linnaeus 1758',
            'rank' => 'subspecies',
        ]);
    }

    private function csvContent($item)
    {
        return [
            'occurrenceID' => $item->observation->id,
            'taxonID' => $item->observation->taxon_id,
            'kingdom' => 'Animalia',
            'phylum' => 'Arthropoda',
            'class' => 'Insecta',
            'order' => 'Coleoptera',
            'family' => 'Cerambycidae',
            'genus' => 'Cerambyx',
            'specificEpithet' => 'cerdo',
            'species' => '"Cerambyx cerdo"',
            'scientificNameAuthorship' => '"Linnaeus 1758"',
            'infraspecificEpithet' => 'cerdo',
            'scientificName' => '"Cerambyx cerdo cerdo"',
            'acceptedNameUsage' => '"Cerambyx cerdo cerdo Linnaeus 1758"',
            'previousIdentifications' => '',
            'taxonRank' => 'subspecies',
            'vernacularName' => '',
            'vernacularNameSerbian' => '',
            'vernacularNameSerbian_latin' => '',
            'vernacularNameCroatian' => '',
            'taxonomicStatus' => 'valid',
            'identifiedBy' => '"Test identifier"',
            'dateIdentified' => $item->observation->created_at->toIso8601String(),
            'basisOfRecord' => 'HumanObservation',
            'dcterms:type' => 'Event',
            'typeOfRecord' => 'Observed',
            'dcterms:rightsHolder' => config('app.name'),
            'dcterms:accessRights' => '"CC BY-SA 4.0"',
            'dcterms:license' => 'https://creativecommons.org/licenses/by-sa/4.0/',
            'minimumElevationInMeters' => '123',
            'maximumElevationInMeters' => '123',
            'recordNumber' => $item->observation->id,
            'verbatimLocality' => '',
            'verbatimEventDate' => '',
            'verbatimElevation' => '',
            'verbatimCoordinates' => '',
            'sex' => 'male',
            'lifeStage' => 'larva',
            'associatedMedia' => '',
            'locality' => '"Test location"',
            'geodeticDatum' => 'WGS84',
            'decimalLatitude' => '12.3456',
            'decimalLongitude' => '15.3456',
            'coordinateUncertaintyInMeters' => '12',
            'georeferencedBy' => '"Test observer"',
            'georeferencedDate' => $item->observation->created_at->toIso8601String(),
            'georeferenceProtocol' => '',
            'individualCount' => '2',
            'recordedBy' => '"Test observer"',
            'eventDate' => '',
            'eventTime' => '10:23',
            'year' => $item->observation->year,
            'month' => $item->observation->month,
            'day' => $item->observation->day,

            'ReferenceID' => '',
            'ReferenceType' => '',
            'ReferenceAuthors' => '',
            'ReferenceEditors' => '',
            'ReferenceYear' => '',
            'ReferencePage' => '',
            'ReferencePages' => '',
            'ReferenceTitle' => '',
            'ReferencePublicationTitle' => '',
            'ReferencePublisher' => '',
            'ReferencePlace' => '',
            'ReferenceDOI' => '',
            'ReferenceLink' => '',
            'ReferencePdf' => '',
            'Reference' => '',
        ];
    }
}
