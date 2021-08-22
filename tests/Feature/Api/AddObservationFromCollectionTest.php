<?php

namespace Tests\Feature\Api;

use App\CollectionObservation;
use App\CollectionSpecimenDisposition;
use App\ObservationIdentificationValidity;
use App\Photo;
use App\SpecimenCollection;
use App\Taxon;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;

class AddObservationFromCollectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    private function validParams($overrides = [])
    {
        return array_map('value', array_merge([
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification' => 'Testudo hermanii',
            'original_identification_validity' => ObservationIdentificationValidity::VALID,
            'verbatim_tag' => 'Some more information',
            'collecting_start_year' => 1990,
            'collecting_start_month' => 3,
            'collecting_end_year' => 1990,
            'collecting_end_month' => 6,
            'taxon_id' => function () {
                return factory(Taxon::class)->create()->id;
            },
            'collection_id' => function () {
                return factory(SpecimenCollection::class)->create()->id;
            },
            'year' => 1990,
            'month' => 5,
            'day' => 12,
            'latitude' => 21.123123,
            'longitude' => 43.123123,
            'location' => 'Gledić Mountains',
            'accuracy' => 10,
            'elevation' => 370,
            'minimum_elevation' => 350,
            'maximum_elevation' => 400,
            'georeferenced_by' => 'Pera Detlić',
            'georeferenced_date' => now()->toDateString(),
            'preparator' => 'Someone who prepared the specimen',
            'preparation_method' => null,
            'collector' => 'Someone who collected the specimen',
            'collecting_method' => null,
            'catalogue_number' => '2DX',
            'cabinet_number' => '1A',
            'box_number' => 'A12',
            'disposition' => CollectionSpecimenDisposition::IN_COLLECTION,
            'type_status' => null,
        ], $overrides));
    }

    /** @test */
    public function guests_cannot_add_observation_from_collection()
    {
        $this->postJson('/api/collection-observations', $this->validParams())
            ->assertUnauthorized();
    }

    /** @test */
    public function curator_can_add_observations_from_collections()
    {
        Passport::actingAs($user = factory(User::class)->create()->assignRoles('curator'));

        $taxon = factory(Taxon::class)->create();
        $collection = factory(SpecimenCollection::class)->create();

        $this->postJson('/api/collection-observations', $this->validParams([
            'taxon_id' => $taxon->id,
            'collection_id' => $collection->id,
        ]))->assertSuccessful();

        $collectionObservation = CollectionObservation::latest()->first();

        $this->assertTrue($collectionObservation->observation->taxon->is($taxon));
        $this->assertEquals('May 12 1990', $collectionObservation->original_date);
        $this->assertEquals('Gledić Mountains', $collectionObservation->original_locality);
        $this->assertEquals('Gledić Mountains', $collectionObservation->original_locality);
        $this->assertEquals('300-500m', $collectionObservation->original_elevation);
        $this->assertEquals('20°22\'44",43°21\'35"', $collectionObservation->original_coordinates);
        $this->assertEquals('Testudo hermanii', $collectionObservation->observation->original_identification);
        $this->assertEquals(ObservationIdentificationValidity::VALID, $collectionObservation->original_identification_validity);
        $this->assertEquals('Some more information', $collectionObservation->verbatim_tag);
        $this->assertEquals(1990, $collectionObservation->collecting_start_year);
        $this->assertEquals(3, $collectionObservation->collecting_start_month);
        $this->assertEquals(1990, $collectionObservation->collecting_end_year);
        $this->assertEquals(6, $collectionObservation->collecting_end_month);
        $this->assertTrue($collectionObservation->collection->is($collection));
        $this->assertEquals(1990, $collectionObservation->observation->year);
        $this->assertEquals(5, $collectionObservation->observation->month);
        $this->assertEquals(12, $collectionObservation->observation->day);
        $this->assertEquals(21.123123, $collectionObservation->observation->latitude);
        $this->assertEquals(43.123123, $collectionObservation->observation->longitude);
        $this->assertEquals('Gledić Mountains', $collectionObservation->observation->location);
        $this->assertTrue($collectionObservation->observation->creator->is($user));
        $this->assertEquals(10, $collectionObservation->observation->accuracy);
        $this->assertEquals(370, $collectionObservation->observation->elevation);
        $this->assertEquals(370, $collectionObservation->observation->elevation);
        $this->assertEquals(350, $collectionObservation->minimum_elevation);
        $this->assertEquals(400, $collectionObservation->maximum_elevation);
        $this->assertEquals('Pera Detlić', $collectionObservation->georeferenced_by);
        $this->assertEquals(now()->toDateString(), $collectionObservation->georeferenced_date->toDateString());
        $this->assertEquals('Someone who prepared the specimen', $collectionObservation->preparator);
        $this->assertNull($collectionObservation->preparation_method);
        $this->assertEquals('Someone who collected the specimen', $collectionObservation->collector);
        $this->assertNull($collectionObservation->collecting_method);
        $this->assertEquals('2DX', $collectionObservation->catalogue_number);
        $this->assertEquals('1A', $collectionObservation->cabinet_number);
        $this->assertEquals('A12', $collectionObservation->box_number);
        $this->assertEquals('in_collection', $collectionObservation->disposition);
        $this->assertNull($collectionObservation->type_status);
    }

    /**
     * @test
     * @dataProvider invalidTaxonId
     * */
    public function taxon_must_be_valid($value)
    {
        Passport::actingAs(factory(User::class)->create()->assignRoles('curator'));

        $response = $this->postJson('/api/collection-observations', $this->validParams([
            'taxon_id' => $value,
        ]));

        $response->assertJsonValidationErrors('taxon_id');
    }

    public function invalidTaxonId()
    {
        yield 'Taxon ID is required' => [null];
        yield 'Taxon ID cannot be array' => [['invalid']];
        yield 'Taxon with given ID must exist' => ['invalid'];
    }

    /**
     * @test
     * @dataProvider invalidCollectionId
     * */
    public function collection_must_be_valid($value)
    {
        Passport::actingAs(factory(User::class)->create()->assignRoles('curator'));

        $response = $this->postJson('/api/collection-observations', $this->validParams([
            'collection_id' => $value,
        ]));

        $response->assertJsonValidationErrors('collection_id');
    }

    public function invalidCollectionId()
    {
        yield 'Collection ID is required' => [null];
        yield 'Collection ID cannot be array' => [['invalid']];
        yield 'Collection with given ID must exist' => ['invalid'];
    }

    /** @test */
    public function photo_can_be_saved_with_observation()
    {
        config(['biologer.photos_per_observation' => 3]);
        config(['biologer.photo_resize_dimension' => null]);

        Queue::fake();
        Storage::fake('public');

        $user = $this->createAuthenticatedUser([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
        File::image('test-image.jpg')->storeAs("uploads/{$user->id}", 'test-image.jpg', 'public');

        $photosCount = Photo::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'observer' => 'John Doe',
            'photos' => [
                [
                    'path' => 'test-image.jpg',
                ],
            ],
        ]));

        $response->assertCreated();

        Photo::assertCount($photosCount + 1);
        $photo = Photo::latest()->first();
        $this->assertEquals("photos/{$photo->id}/test-image.jpg", $photo->path);
        $this->assertNotEmpty($photo->url);
        $this->assertTrue(Storage::disk('public')->exists($photo->path));
        $this->assertEquals('John Doe', $photo->author);
    }

    private function createAuthenticatedUser($data = [])
    {
        return $this->setTestClientMock(Passport::actingAs(factory(User::class)->create($data)));
    }

    private function makeAuthenticatedUser($data = [])
    {
        return $this->setTestClientMock(Passport::actingAs(factory(User::class)->make($data)));
    }

    private function setTestClientMock($user)
    {
        $user->token()
            ->shouldReceive('getAttribute')
            ->with('client')
            ->andReturn(new class {
                public $name = 'Test Client App';
            });

        return $user;
    }
}
