<?php

namespace Tests\Feature\Api;

use App\User;
use App\Photo;
use App\Stage;
use App\Taxon;
use App\License;
use Tests\TestCase;
use Tests\ObservationFactory;
use Laravel\Passport\Passport;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateFieldObservationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->seed('RolesTableSeeder');
    }

    /**
     * Valid observation data.
     *
     * @param  array  $overrides
     * @return array
     */
    protected function validParams($overrides = [])
    {
        return array_merge([
            'taxon_id' => null,
            'year' => '2017',
            'month' => '7',
            'day' => '15',
            'location' => 'Novi Sad',
            'latitude' => '45.251667',
            'longitude' => '19.836944',
            'accuracy' => '100',
            'elevation' => '80',
            'source' => 'John Doe',
            'taxon_suggestion' => null,
            'time' => '12:30',
            'sex' => 'male',
            'project' => 'The Big Project',
            'found_on' => 'Leaf of birch',
            'reason' => 'Testing',
        ], $overrides);
    }

    /** @test */
    public function field_observation_can_be_updated_but_will_return_to_pending_status()
    {
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $fieldObservation = ObservationFactory::createFieldObservation([
            'created_by_id' => $user->id,
            'taxon_id' => factory(Taxon::class),
        ]);

        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'taxon_id' => $taxon->id,
                'elevation' => 1000,
                'taxon_suggestion' => 'New taxon suggestion',
            ])
        );

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'taxon_id' => $taxon->id,
                'elevation' => 1000,
                'taxon_suggestion' => 'Cerambyx cerdo', // We prefer name of the chosen taxon.
            ],
        ]);

        tap($fieldObservation->fresh(), function ($fieldObservation) use ($taxon) {
            $this->assertTrue($fieldObservation->observation->taxon->is($taxon));
            $this->assertEquals('Cerambyx cerdo', $fieldObservation->taxon_suggestion);

            $this->assertEquals(1000, $fieldObservation->observation->elevation);
            $this->assertEquals('The Big Project', $fieldObservation->observation->project);
            $this->assertEquals('Leaf of birch', $fieldObservation->observation->found_on);
            $this->assertTrue($fieldObservation->isPending());
        });
    }

    /** @test */
    public function activity_log_entry_is_added_when_field_observation_is_updated()
    {
        Storage::fake('public');
        $this->artisan('db:seed', ['--class' => 'StagesTableSeeder']);
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx scopolii']);
        $taxon->stages()->sync($stages = Stage::all());

        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'taxon_id' => $taxon->id,
            'created_by_id' => $user->id,
            'stage_id' => $stages->first()->id,
            'elevation' => 500,
        ], [
            'taxon_suggestion' => $taxon->name,
            'license' => License::findByName('CC BY-SA 4.0')->id,
            'found_dead' => true,
            'found_dead_note' => 'Note on dead',
            'time' => '09:00',
        ]);
        $fieldObservation->photos()->sync(factory(Photo::class)->create());
        $activityCount = $fieldObservation->activity()->count();

        $uploadedPhoto = File::image(str_random().'.jpg')
            ->store('uploads/'.$user->id, [
                'disk' => 'public',
            ]);

        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'taxon_id' => null,
                'elevation' => 1000,
                'taxon_suggestion' => 'New taxon suggestion',
                'reason' => 'Just testin\' :)',
                'stage_id' => null,
                'data_license' => License::findByName('CC BY-NC-SA 4.0')->id,
                'found_dead' => false,
                'time' => null,
                'photos' => [],
            ])
        );

        $response->assertStatus(200);

        tap($fieldObservation->fresh(), function ($fieldObservation) use ($activityCount, $user, $stages) {
            $fieldObservation->activity->assertCount($activityCount + 1);
            $activity = $fieldObservation->activity->latest()->first();

            $this->assertEquals('updated', $activity->description);
            $this->assertTrue($activity->causer->is($user));
            $this->assertArraySubset([
                'elevation' => 500,
                'taxon' => 'Cerambyx scopolii',
                'stage' => ['label' => 'stages.'.$stages->first()->name],
                'data_license' => [
                    'label' => 'licenses.CC BY-SA 4.0',
                ],
                'time' => '09:00',
                'found_dead' => [
                    'label' => 'Yes',
                ],
                'photos' => null,
            ], $activity->changes()->get('old'));
            $this->assertEquals('Just testin\' :)', $activity->getExtraProperty('reason'));
        });
    }

    /** @test */
    public function time_is_not_logged_if_not_changed()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'created_by_id' => $user->id,
        ], [
            'time' => '09:00',
        ]);
        $activityCount = $fieldObservation->activity()->count();

        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'time' => '09:00',
                'latitude' => '43.123123',
                'longitude' => '43.123123',
                'elevation' => '239',
                'accuracy' => '30',
            ])
        );

        $response->assertStatus(200);

        tap($fieldObservation->fresh(), function ($fieldObservation) use ($activityCount) {
            $fieldObservation->activity->assertCount($activityCount + 1);
            $activity = $fieldObservation->activity->latest()->first();

            $this->assertArrayNotHasKey('time', $activity->changes()->get('old'));
        });
    }

    /** @test */
    public function time_is_logged_if_changed()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'created_by_id' => $user->id,
        ], [
            'time' => '09:00',
        ]);
        $activityCount = $fieldObservation->activity()->count();

        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'time' => '10:00',
            ])
        );

        $response->assertStatus(200);

        tap($fieldObservation->fresh(), function ($fieldObservation) use ($activityCount) {
            $fieldObservation->activity->assertCount($activityCount + 1);
            $activity = $fieldObservation->activity->latest()->first();

            $this->assertArrayHasKey('time', $activity->changes()->get('old'));
        });
    }

    /** @test */
    public function field_observation_cannot_be_updated_by_other_regular_user()
    {
        Passport::actingAs(factory(User::class)->create());
        $observation = ObservationFactory::createFieldObservation([
            'created_by_id' => factory(User::class),
        ]);

        $response = $this->putJson(
            "/api/field-observations/{$observation->id}",
            $this->validParams([
                'elevation' => 1000,
                'observer' => 'New observer',
                'taxon_suggestion' => 'New taxon suggestion',
            ])
        );

        $response->assertForbidden();

        tap($observation->fresh(), function ($fieldObservation) {
            $this->assertNotEquals(1000, $fieldObservation->observation->elevation);
            $this->assertNotEquals('New observer', $fieldObservation->observation->observer);
            $this->assertNotEquals('New taxon suggestion', $fieldObservation->taxon_suggestion);
        });
    }

    /** @test */
    public function field_observation_can_be_updated_by_admin()
    {
        $user = factory(User::class)->create()->assignRoles('admin');
        Passport::actingAs($user);
        $fieldObservation = ObservationFactory::createFieldObservation([
            'created_by_id' => $user->id,
        ]);

        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'elevation' => 1000,
                'observer' => 'New observer',
                'taxon_suggestion' => 'New taxon suggestion',
            ])
        );

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'elevation' => 1000,
                'observer' => 'New observer',
                'taxon_suggestion' => 'New taxon suggestion',
            ],
        ]);

        tap($fieldObservation->fresh(), function ($fieldObservation) {
            $this->assertEquals(1000, $fieldObservation->observation->elevation);
            $this->assertEquals('New observer', $fieldObservation->observation->observer);
            $this->assertEquals('New taxon suggestion', $fieldObservation->taxon_suggestion);
        });
    }

    /** @test */
    public function updating_photos()
    {
        Storage::fake('public');

        $user = factory(User::class)->create();

        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation([
            'created_by_id' => $user->id,
            'elevation' => 500,
        ], [
            'license' => License::findByName('CC BY-SA 4.0')->id,
            'found_dead' => true,
            'found_dead_note' => 'Note on dead',
            'time' => '09:00',
        ]);

        $existingPhoto = factory(Photo::class)->create();
        $fieldObservation->photos()->sync($existingPhoto);

        $uploadedPhoto = File::image('new-test-image.jpg')
            ->storeAs('uploads/'.$user->id, 'new-test-image.jpg', 'public');

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
    public function either_id_or_path_must_be_given_when_updating_photos()
    {
        Storage::fake('public');

        $user = factory(User::class)->create();

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

    /** @test */
    public function admin_can_submit_observer_by_users_id()
    {
        $this->seed('RolesTableSeeder');
        $user = factory(User::class)->create()->assignRoles('admin');
        $anotherUser = factory(User::class)->create(['first_name' => 'Jane', 'last_name' => 'Doe']);
        Passport::actingAs($user);

        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation();

        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'observed_by_id' => $anotherUser->id,
            ])
        );

        $response->assertSuccessful();

        tap($fieldObservation->fresh(), function ($fieldObservation) use ($anotherUser) {
            $this->assertTrue($fieldObservation->observedBy->is($anotherUser));
            $this->assertEquals($fieldObservation->observer, 'Jane Doe');
        });
    }

    /** @test */
    public function admin_can_submit_identifier_by_users_id()
    {
        $this->seed('RolesTableSeeder');
        $user = factory(User::class)->create()->assignRoles('admin');
        $anotherUser = factory(User::class)->create(['first_name' => 'Jane', 'last_name' => 'Doe']);
        Passport::actingAs($user);

        $fieldObservation = ObservationFactory::createUnapprovedFieldObservation();

        $response = $this->putJson(
            "/api/field-observations/{$fieldObservation->id}",
            $this->validParams([
                'identified_by_id' => $anotherUser->id,
            ])
        );

        $response->assertSuccessful();

        tap($fieldObservation->fresh(), function ($fieldObservation) use ($anotherUser) {
            $this->assertTrue($fieldObservation->identifiedBy->is($anotherUser));
            $this->assertEquals($fieldObservation->identifier, 'Jane Doe');
        });
    }
}
