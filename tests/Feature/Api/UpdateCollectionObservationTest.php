<?php

namespace Tests\Feature\Api;

use App\CollectionObservation;
use App\License;
use App\Observation;
use App\ObservationIdentificationValidity;
use App\Photo;
use App\SpecimenCollection;
use App\Stage;
use App\Taxon;
use App\User;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\ObservationFactory;
use Tests\TestCase;

class UpdateCollectionObservationTest extends TestCase
{
    /** @test */
    public function guest_cannot_update_observation()
    {
        $collectionObservation = $this->createCollectionObservation();

        $response = $this->putJson("/api/collection-observations/{$collectionObservation->id}", $this->validParams());

        $response->assertUnauthorized();
    }

    /** @test */
    public function unauthorized_user_cannot_update_observation()
    {
        $collectionObservation = $this->createCollectionObservation();

        Passport::actingAs(User::factory()->create());
        $response = $this->putJson("/api/collection-observations/{$collectionObservation->id}", $this->validParams());

        $response->assertForbidden();
    }

    /** @test */
    public function curator_can_update_collection_observations()
    {
        $this->seed('RolesTableSeeder');
        $collectionObservation = $this->createCollectionObservation();
        $taxon = Taxon::factory()->create();
        $stage = Stage::factory()->create();
        $specimenCollection = SpecimenCollection::factory()->create();

        Passport::actingAs(User::factory()->create()->assignRoles('curator'));
        $response = $this->withoutExceptionHandling()->putJson("/api/collection-observations/{$collectionObservation->id}", $this->validParams([
            'taxon_id' => $taxon->id,
            'stage_id' => $stage->id,
            'collection_id' => $specimenCollection->id,
        ]));

        $response->assertSuccessful();
        $collectionObservation = $collectionObservation->refresh();
        $this->assertEquals('May 13 1991', $collectionObservation->original_date);
        $this->assertEquals('Balkan Mountain', $collectionObservation->original_locality);
        $this->assertEquals('32-50m', $collectionObservation->original_elevation);
        $this->assertEquals('21°22\'44",41°21\'35"', $collectionObservation->original_coordinates);
        $this->assertEquals('Testuduo hermanii', $collectionObservation->observation->original_identification);
        $this->assertEquals(ObservationIdentificationValidity::INVALID, $collectionObservation->original_identification_validity);
        $this->assertTrue($collectionObservation->observation->taxon->is($taxon));
        $this->assertEquals(1991, $collectionObservation->observation->year);
        $this->assertEquals(5, $collectionObservation->observation->month);
        $this->assertEquals(13, $collectionObservation->observation->day);
        $this->assertTrue($collectionObservation->collection->is($specimenCollection));
        $this->assertEquals(22.123123, $collectionObservation->observation->latitude);
        $this->assertEquals(44.123123, $collectionObservation->observation->longitude);
        $this->assertEquals('Balkan Mountain', $collectionObservation->observation->location);
        $this->assertEquals(20, $collectionObservation->observation->accuracy);
        $this->assertEquals(37, $collectionObservation->observation->elevation);
        $this->assertEquals(32, $collectionObservation->minimum_elevation);
        $this->assertEquals(50, $collectionObservation->maximum_elevation);
        $this->assertEquals('Scooby Doo', $collectionObservation->georeferenced_by);
        $this->assertEquals(now()->subDay()->toDateString(), $collectionObservation->georeferenced_date->toDateString());
        $this->assertEquals('female', $collectionObservation->observation->sex);
        $this->assertTrue($collectionObservation->observation->stage->is($stage));
        $this->assertEquals('Some other information', $collectionObservation->verbatim_tag);
        $this->assertEquals(1990, $collectionObservation->collecting_start_year);
        $this->assertEquals(3, $collectionObservation->collecting_start_month);
        $this->assertEquals(1990, $collectionObservation->collecting_end_year);
        $this->assertEquals(6, $collectionObservation->collecting_end_month);
    }

    protected function validParams($overrides = [])
    {
        return array_map('value', array_merge([
            'original_date' => 'May 13 1991',
            'original_locality' => 'Balkan Mountain',
            'original_elevation' => '32-50m',
            'original_coordinates' => '21°22\'44",41°21\'35"',
            'original_identification' => 'Testuduo hermanii',
            'original_identification_validity' => ObservationIdentificationValidity::INVALID,
            'verbatim_tag' => 'Some other information',
            'collecting_start_year' => 1990,
            'collecting_start_month' => 3,
            'collecting_end_year' => 1990,
            'collecting_end_month' => 6,
            'taxon_id' => function () {
                return Taxon::factory()->create()->id;
            },
            'year' => 1991,
            'month' => 5,
            'day' => 13,
            'collection_id' => function () {
                return SpecimenCollection::factory()->create()->id;
            },
            'latitude' => 22.123123,
            'longitude' => 44.123123,
            'location' => 'Balkan Mountain',
            'accuracy' => 20,
            'elevation' => 37,
            'minimum_elevation' => 32,
            'maximum_elevation' => 50,
            'georeferenced_by' => 'Scooby Doo',
            'georeferenced_date' => now()->subDay()->toDateString(),
            'reason' => 'Test',
            'sex' => 'female',
            'stage_id' => function () {
                return Stage::factory()->create()->id;
            },
        ], $overrides));
    }

    protected function createCollectionObservation()
    {
        $collectionObservation = CollectionObservation::factory()->create([
            'original_date' => 'May 12 1990',
            'original_locality' => 'Gledić Mountains',
            'original_elevation' => '300-500m',
            'original_coordinates' => '20°22\'44",43°21\'35"',
            'original_identification_validity' => ObservationIdentificationValidity::VALID,
            'verbatim_tag' => 'Some information',
            'collection_id' => SpecimenCollection::factory()->create()->id,
            'minimum_elevation' => 350,
            'maximum_elevation' => 400,
            'georeferenced_by' => 'Pera Detlić',
            'georeferenced_date' => now()->toDateString(),
        ]);

        $collectionObservation->observation()->save(Observation::factory()->make([
            'original_identification' => 'Testudo hermanii',
            'taxon_id' => Taxon::factory()->create()->id,
            'year' => 1990,
            'month' => 5,
            'day' => 12,
            'latitude' => 21.123123,
            'longitude' => 43.123123,
            'location' => 'Gledić Mountains',
            'accuracy' => 10,
            'elevation' => 370,
            'created_by_id' => User::factory()->create()->id,
            'sex' => 'male',
            'stage_id' => Stage::factory()->create(['name' => 'Old stage'])->id,
        ]));

        return $collectionObservation;
    }

    /** @test */
    public function updating_photos()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'created_by_id' => $user->id,
            'elevation' => 500,
        ], [
            'license' => License::findByName('CC BY-SA 4.0')->id,
            'found_dead' => true,
            'found_dead_note' => 'Note on dead',
            'time' => '09:00',
        ]);

        $existingPhoto = Photo::factory()->create();
        $fieldObservation->photos()->sync($existingPhoto);

        File::image('new-test-image.jpg')->storeAs('uploads/'.$user->id, 'new-test-image.jpg', 'public');

        Passport::actingAs($user);

        $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'photos' => [
                    [
                        'id' => $existingPhoto->id,
                    ],
                    [
                        'path' => 'new-test-image.jpg',
                    ],
                ],
            ])
        );

        $this->assertEquals(2, $fieldObservation->photos->count());
        $this->assertTrue($fieldObservation->photos->contains($existingPhoto));
    }

    /** @test */
    public function updating_photo_license()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'created_by_id' => $user->id,
            'elevation' => 500,
        ], [
            'license' => License::CC_BY_SA,
            'found_dead' => true,
            'found_dead_note' => 'Note on dead',
            'time' => '09:00',
        ]);

        $existingPhoto = Photo::factory()->create(['license' => License::CC_BY_SA]);
        $fieldObservation->photos()->sync($existingPhoto);

        Passport::actingAs($user);

        $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'photos' => [
                    [
                        'id' => $existingPhoto->id,
                        'license' => License::CLOSED,
                    ],
                ],
            ])
        );

        $this->assertEquals(1, $fieldObservation->photos->count());
        $this->assertEquals(License::CLOSED, $fieldObservation->photos->first()->license);
    }

    /** @test */
    public function either_id_or_path_must_be_given_when_updating_photos()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'created_by_id' => $user->id,
            'elevation' => 500,
        ], [
            'license' => License::findByName('CC BY-SA 4.0')->id,
            'found_dead' => true,
            'found_dead_note' => 'Note on dead',
            'time' => '09:00',
        ]);

        Passport::actingAs($user);

        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'photos' => [
                    [
                        'something_unneeded' => 'new-test-image.jpg',
                    ],
                ],
            ])
        );

        $response->assertJsonValidationErrors(['photos.0.id', 'photos.0.path']);
    }
}
