<?php

namespace Tests\Unit\Exports\FieldObservations;

use App\User;
use App\Stage;
use App\Taxon;
use App\License;
use Tests\TestCase;
use App\ObservationType;
use App\Jobs\PerformExport;
use Tests\ObservationFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\ActivityLog\FieldObservationDiff;
use Box\Spout\Common\Helper\EncodingHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exports\FieldObservations\DarwinCoreFieldObservationsExport;

class DarwinCoreFieldObservationsExportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function all_field_observations_are_exported_in_darwin_core_to_a_csv_file()
    {
        Carbon::setTestNow(Carbon::now());
        Storage::fake('local');
        $this->seed('StagesTableSeeder');
        $this->seed('ObservationTypesTableSeeder');

        $this->actingAs(factory(User::class)->create());

        $taxon = $this->createTaxon()['subspecies'];

        $observation = ObservationFactory::createFieldObservation([
            'created_by_id' => factory(User::class)->create()->id,
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

        $export = DarwinCoreFieldObservationsExport::createFiltered();

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

    /** @test */
    public function field_observations_can_be_without_identification()
    {
        Carbon::setTestNow(Carbon::now());
        Storage::fake('local');
        $this->seed('StagesTableSeeder');
        $this->seed('ObservationTypesTableSeeder');

        $this->actingAs(factory(User::class)->create());

        $observation = ObservationFactory::createFieldObservation([
            'created_by_id' => factory(User::class)->create()->id,
            'taxon_id' => null,
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

        $export = DarwinCoreFieldObservationsExport::createFiltered();

        (new PerformExport($export))->handle();

        Storage::disk('local')->assertExists($export->path());

        $expectedKeyValuePairs = collect($this->csvContent($observation, [
            'kingdom' => '',
            'phylum' => '',
            'class' => '',
            'order' => '',
            'family' => '',
            'genus' => '',
            'specificEpithet' => '',
            'species' => '',
            'scientificNameAuthorship' => '',
            'infraspecificEpithet' => '',
            'scientificName' => '',
            'acceptedNameUsage' => '',
            'previousIdentifications' => '',
            'taxonRank' => '',
            'taxonomicStatus' => '',
            'identifiedBy' => '',
            'dateIdentified' => '',
        ]));

        $this->assertEquals(
            EncodingHelper::BOM_UTF8.
            $expectedKeyValuePairs->keys()->implode(',')."\n".
            $expectedKeyValuePairs->implode(',')."\n",
            Storage::disk('local')->get($export->path())
        );
    }

    /** @test */
    public function species_identified_with_identification_history()
    {
        Carbon::setTestNow(Carbon::now());
        Storage::fake('local');
        $this->seed('StagesTableSeeder');
        $this->seed('ObservationTypesTableSeeder');

        $this->actingAs($user = factory(User::class)->create());
        $species = $this->createTaxon()['species'];

        $fieldObservation = ObservationFactory::createFieldObservation([
            'created_by_id' => factory(User::class)->create()->id,
            'taxon_id' => null,
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
            'taxon_suggestion' => 'Cerambyx sp.',
        ]);

        $fieldObservation = $this->changeIdentificationAndLogActivity($fieldObservation, $species, $user);

        $type = ObservationType::whereSlug('observed')->first();
        $fieldObservation->observation->types()->attach($type);

        $export = DarwinCoreFieldObservationsExport::createFiltered();

        (new PerformExport($export))->handle();

        Storage::disk('local')->assertExists($export->path());

        $expectedKeyValuePairs = collect($this->csvContent($fieldObservation, [
            'scientificNameAuthorship' => '"Linnaeus 1758"',
            'infraspecificEpithet' => '',
            'scientificName' => '"Cerambyx sp."',
            'acceptedNameUsage' => '"Cerambyx cerdo Linnaeus 1758"',
            'previousIdentifications' => '"Cerambyx sp."',
            'taxonRank' => 'species',
            'taxonomicStatus' => 'valid',
            'identifiedBy' => '"Test identifier"',
            'dateIdentified' => $fieldObservation->load('activity')->activity->first()->created_at->toIso8601String(),
        ]));

        $this->assertEquals(
            EncodingHelper::BOM_UTF8.
            $expectedKeyValuePairs->keys()->implode(',')."\n".
            $expectedKeyValuePairs->implode(',')."\n",
            Storage::disk('local')->get($export->path())
        );
    }

    private function changeIdentificationAndLogActivity($fieldObservation, $taxon, $user)
    {
        $oldFieldObservation = $fieldObservation->load('observation.types', 'observation.photos')->replicate();

        $fieldObservation->load('observation')->observation->update(['taxon_id' => $taxon->id]);
        $fieldObservation->update(['taxon_suggestion' => $taxon->name]);

        $beforeUpdate = FieldObservationDiff::changes($fieldObservation, $oldFieldObservation);

        activity()->performedOn($fieldObservation)
           ->causedBy($user)
           ->withProperty('old', $beforeUpdate)
           ->withProperty('reason', 'Testing')
           ->log('updated');

        return $fieldObservation;
    }

    private function createTaxon()
    {
        $kingdom = factory(Taxon::class)->create([
            'name' => 'Animalia',
            'rank' => 'kingdom',
        ]);

        $phylum = factory(Taxon::class)->create([
            'parent_id' => $kingdom->id,
            'name' => 'Arthropoda',
            'rank' => 'phylum',
        ]);

        $class = factory(Taxon::class)->create([
            'parent_id' => $phylum->id,
            'name' => 'Insecta',
            'rank' => 'class',
        ]);

        $order = factory(Taxon::class)->create([
            'parent_id' => $class->id,
            'name' => 'Coleoptera',
            'rank' => 'order',
        ]);

        $family = factory(Taxon::class)->create([
            'parent_id' => $order->id,
            'name' => 'Cerambycidae',
            'rank' => 'family',
        ]);

        $genus = factory(Taxon::class)->create([
            'parent_id' => $family->id,
            'name' => 'Cerambyx',
            'rank' => 'genus',
        ]);

        $species = factory(Taxon::class)->create([
            'parent_id' => $genus->id,
            'name' => 'Cerambyx cerdo',
            'rank' => 'species',
            'author' => 'Linnaeus 1758',
        ]);

        $subspecies = factory(Taxon::class)->create([
            'parent_id' => $species->id,
            'name' => 'Cerambyx cerdo cerdo',
            'author' => 'Linnaeus 1758',
            'rank' => 'subspecies',
        ]);

        return [
            'kingdom' => $kingdom,
            'phylum' => $phylum,
            'class' => $class,
            'order' => $order,
            'family' => $family,
            'genus' => $genus,
            'species' => $species,
            'subspecies' => $subspecies,
        ];
    }

    private function csvContent($item, $overrides = [])
    {
        return array_merge([
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
        ], $overrides);
    }
}
