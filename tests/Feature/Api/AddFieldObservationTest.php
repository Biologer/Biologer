<?php

namespace Tests\Feature\Api;

use App\User;
use App\Photo;
use App\Taxon;
use Tests\TestCase;
use App\Observation;
use App\FieldObservation;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Http\Testing\File;
use App\Jobs\ProcessUploadedPhoto;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FieldObservationForApproval;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddFieldObservationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        Notification::fake();
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
            'observer' => 'John Doe',
            'identifier' => 'Ident Doe',
            'note' => 'Some comment',
            'sex' => 'male',
            'number' => '2',
            'time' => '12:00',
            'project' => 'The Big Project',
            'found_on' => 'Leaf of birch',
        ], $overrides);
    }

    /** @test */
    public function guests_cannot_add_new_field_observations()
    {
        $fieldObservationsCount = FieldObservation::count();
        $observationsCount = Observation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams());

        $response->assertUnauthorized();

        FieldObservation::assertCount($fieldObservationsCount);
        Observation::assertCount($observationsCount);
    }

    /** @test */
    public function authenticated_user_can_add_field_observation()
    {
        $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => $taxon->id,
        ]));

        $response->assertCreated();

        FieldObservation::assertCount($fieldObservationsCount + 1);
        $fieldObservation = FieldObservation::latest()->first();

        $this->assertEquals('12:00', $fieldObservation->time->format('H:i'));
        $this->assertEquals('Cerambyx cerdo', $fieldObservation->taxon_suggestion);

        tap($fieldObservation->observation, function ($observation) use ($user, $taxon) {
            $this->assertTrue($observation->taxon->is($taxon));
            $this->assertEquals($user->full_name, $observation->observer);
            $this->assertEquals(2017, $observation->year);
            $this->assertEquals(7, $observation->month);
            $this->assertEquals(15, $observation->day);
            $this->assertEquals('Novi Sad', $observation->location);
            $this->assertEquals(45.251667, $observation->latitude);
            $this->assertEquals(19.836944, $observation->longitude);
            $this->assertEquals(100, $observation->accuracy);
            $this->assertEquals(80, $observation->elevation);
            $this->assertNull($observation->approved_at);
            $this->assertEquals($user->id, $observation->created_by_id);
            $this->assertEquals('Some comment', $observation->note);
            $this->assertEquals('male', $observation->sex);
            $this->assertEquals(2, $observation->number);
            $this->assertEquals('The Big Project', $observation->project);
            $this->assertEquals('Leaf of birch', $observation->found_on);
            $this->assertEquals('Cerambyx cerdo', $observation->original_identification);
        });
    }

    /** @test */
    public function activity_log_entry_is_added_when_field_observation_is_added()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams());

        $response->assertCreated();

        FieldObservation::assertCount($fieldObservationsCount + 1);
        $fieldObservation = FieldObservation::latest()->first();

        $fieldObservation->activity->assertCount(1);
        $activity = $fieldObservation->activity->latest()->first();

        $this->assertEquals('created', $activity->description);
        $this->assertTrue($activity->causer->is($user));
    }

    /** @test */
    public function users_full_name_as_source_when_source_is_not_provided()
    {
        Passport::actingAs(factory(User::class)->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]));

        $this->postJson('/api/field-observations', $this->validParams([
            'source' => '',
        ]))->assertCreated();

        $this->assertEquals('Jane Doe', FieldObservation::latest()->first()->observation->observer);
    }

    /** @test */
    public function year_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'year' => null,
        ]))->assertValidationErrors('year');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function year_must_be_valid_year()
    {
        Passport::actingAs(factory(User::class)->make());

        $this->postJson('/api/field-observations', $this->validParams([
            'year' => '1aa2',
        ]))->assertValidationErrors('year');
    }

    /** @test */
    public function year_cannot_be_in_the_future()
    {
        Passport::actingAs(factory(User::class)->make());

        $this->postJson('api/field-observations', $this->validParams([
            'year' => date('Y') + 1,
        ]))->assertValidationErrors('year');
    }

    /** @test */
    public function month_cannot_be_in_the_future()
    {
        Passport::actingAs(factory(User::class)->make());

        $this->postJson('api/field-observations', $this->validParams([
            'year' => date('Y'),
            'month' => date('m') + 1,
        ]))->assertValidationErrors('month');
    }

    /** @test */
    public function month_cannot_be_string()
    {
        Passport::actingAs(factory(User::class)->make());

        $this->postJson('api/field-observations', $this->validParams([
            'year' => date('Y'),
            'month' => 'invalid',
        ]))->assertValidationErrors('month');
    }

    /** @test */
    public function month_cannot_be_negative_number()
    {
        Passport::actingAs(factory(User::class)->make());

        $this->postJson('api/field-observations', $this->validParams([
            'year' => date('Y'),
            'month' => '-1',
        ]))->assertValidationErrors('month');
    }

    /** @test */
    public function day_cannot_be_in_the_future_longer_than_a_day()
    {
        Passport::actingAs(factory(User::class)->make());

        $now = Carbon::now();

        $this->postJson('api/field-observations', $this->validParams([
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day + 2,
        ]))->assertValidationErrors('day');
    }

    /** @test */
    public function day_cannot_be_negative_number()
    {
        Passport::actingAs(factory(User::class)->make());

        $now = Carbon::now();

        $this->postJson('api/field-observations', $this->validParams([
            'year' => $now->year,
            'month' => $now->month,
            'day' => '-1',
        ]))->assertValidationErrors('day');
    }

    /** @test */
    public function day_cannot_be_string()
    {
        Passport::actingAs(factory(User::class)->make());

        $now = Carbon::now();

        $this->postJson('api/field-observations', $this->validParams([
            'year' => $now->year,
            'month' => $now->month,
            'day' => 'invalid',
        ]))->assertValidationErrors('day');
    }

    /** @test */
    public function latitude_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => null,
        ]))->assertValidationErrors('latitude');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function latitude_must_be_number_less_than_90()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => '91',
        ]))->assertValidationErrors('latitude');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function latitude_must_be_number_breate_than_negative_90()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => '-91',
        ]))->assertValidationErrors('latitude');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function longitude_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'longitude' => null,
        ]))->assertValidationErrors('longitude');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function longitude_must_be_number_less_than_180()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'longitude' => '181',
        ]))->assertValidationErrors('longitude');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function longitude_must_be_number_greater_than_negative_180()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'longitude' => '-181',
        ]))->assertValidationErrors('longitude');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function elevation_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'elevation' => null,
        ]))->assertValidationErrors('elevation');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function elevation_must_be_number()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'elevation' => 'aaa',
        ]))->assertValidationErrors('elevation');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function accuracy_is_optional_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'accuracy' => null,
        ]))->assertSuccessful();

        FieldObservation::assertCount($fieldObservationsCount + 1);
    }

    /** @test */
    public function accuracy_must_be_number()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'accuracy' => 'aaa',
        ]))->assertValidationErrors('accuracy');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function taxon_is_optional()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => null,
        ]))->assertCreated();

        FieldObservation::assertCount($fieldObservationsCount + 1);
    }

    /** @test */
    public function fails_if_taxon_does_not_exist()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => '999999999',
        ]))->assertValidationErrors('taxon_id');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function observation_is_stored_with_correct_taxon()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();
        $taxon = factory(Taxon::class)->create();

        $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => $taxon->id,
        ]))->assertCreated();

        FieldObservation::assertCount($fieldObservationsCount + 1);
        $this->assertEquals($taxon->id, Observation::first()->taxon_id);
    }

    /** @test */
    public function taxon_suggestion_is_stored()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'taxon_suggestion' => 'Cerambyx cerdo',
        ]))->assertCreated();

        FieldObservation::assertCount($fieldObservationsCount + 1);
        $this->assertEquals('Cerambyx cerdo', FieldObservation::latest()->first()->taxon_suggestion);
    }

    /** @test */
    public function mgrs_field_is_calculated_automaticaly()
    {
        Passport::actingAs(factory(User::class)->create());

        $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => '43.60599592',
            'longitude' => '21.30373179',
        ]))->assertCreated();

        tap(FieldObservation::first()->observation, function ($observation) {
            $this->assertEquals('34TEP22', $observation->mgrs10k);
        });
    }

    /** @test */
    public function mgrs_field_cannot_be_calculated_in_polar_region()
    {
        Passport::actingAs(factory(User::class)->create());

        $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => '85.0',
            'longitude' => '21.30373179',
        ]))->assertCreated();

        tap(FieldObservation::first()->observation, function ($observation) {
            $this->assertNull($observation->mgrs10k);
        });
    }

    /** @test */
    public function photo_can_be_saved_with_observation()
    {
        config(['alciphron.photos_per_observation' => 3]);
        config(['alciphron.photo_size_dimension' => null]);

        Queue::fake();
        Storage::fake('public');

        Passport::actingAs($user = factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]));
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

    /** @test */
    public function photos_are_queued_to_be_processed_if_needed()
    {
        config(['alciphron.photo_size_dimension' => 800]);

        Queue::fake();
        Storage::fake('public');

        Passport::actingAs($user = factory(User::class)->create());
        File::image('test-image.jpg', 400, 400)->storeAs("uploads/{$user->id}", 'test-image.jpg', 'public');

        $photosCount = Photo::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'observer' => 'John Doe',
            'photos' => [
                [
                    'path' => 'test-image.jpg',
                    'crop' => ['width' => 100, 'height' => 100, 'x' => 100, 'y' => 100],
                ],
            ],
        ]))->assertCreated();

        Photo::assertCount($photosCount + 1);
        $photo = Photo::latest()->first();
        Queue::assertPushed(ProcessUploadedPhoto::class, function ($job) use ($photo) {
            return $job->photo->is($photo)
                && $job->crop === ['width' => 100, 'height' => 100, 'x' => 100, 'y' => 100];
        });
    }

    /** @test */
    public function maximum_number_of_photos_that_can_be_saved_with_observation_can_be_set()
    {
        config(['alciphron.photos_per_observation' => 3]);

        Passport::actingAs(factory(User::class)->make());
        Storage::fake('public');
        $photosCount = Photo::count();
        File::image('test-image1.jpg')->storeAs('uploads', 'test-image1.jpg', 'public');
        File::image('test-image2.jpg')->storeAs('uploads', 'test-image2.jpg', 'public');
        File::image('test-image3.jpg')->storeAs('uploads', 'test-image3.jpg', 'public');
        File::image('test-image4.jpg')->storeAs('uploads', 'test-image4.jpg', 'public');

        $this->postJson('/api/field-observations', $this->validParams([
            'photos' => [
                [
                    'path' => 'test-image1.jpg',
                ],
                [
                    'path' => 'test-image2.jpg',
                ],
                [
                    'path' => 'test-image3.jpg',
                ],
                [
                    'path' => 'test-image4.jpg',
                ],
            ],
        ]))->assertValidationErrors('photos');

        Photo::assertCount($photosCount);
    }

    /** @test */
    public function photos_can_be_null()
    {
        Passport::actingAs(factory(User::class)->create());

        $this->postJson('/api/field-observations', $this->validParams([
            'photos' => null,
        ]))->assertCreated();
    }

    /** @test */
    public function sex_is_optional()
    {
        Passport::actingAs(factory(User::class)->create());

        $this->postJson('/api/field-observations', $this->validParams([
            'sex' => null,
        ]))->assertCreated();
    }

    /** @test */
    public function sex_can_only_be_one_of_available_values()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'sex' => 'invalid',
        ]))->assertValidationErrors('sex');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function time_is_optional()
    {
        Passport::actingAs(factory(User::class)->create());

        $this->postJson('/api/field-observations', $this->validParams([
            'time' => null,
        ]))->assertCreated();
    }

    /** @test */
    public function time_must_be_in_correct_format()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'time' => 'invalid',
        ]))->assertValidationErrors('time');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function admin_can_submit_observer_by_users_id()
    {
        $this->seed('RolesTableSeeder');
        $user = factory(User::class)->create()->assignRoles('admin');
        $anotherUser = factory(User::class)->create(['first_name' => 'Jane', 'last_name' => 'Doe']);
        Passport::actingAs($user);

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'observed_by_id' => $anotherUser->id,
        ]));

        $response->assertCreated();

        $fieldObservation = FieldObservation::latest()->first();
        $this->assertTrue($fieldObservation->observedBy->is($anotherUser));
        $this->assertEquals($fieldObservation->observer, 'Jane Doe');
    }

    /** @test */
    public function admin_can_submit_identifier_by_users_id_if_there_is_identification()
    {
        $this->seed('RolesTableSeeder');
        $user = factory(User::class)->create()->assignRoles('admin');
        $anotherUser = factory(User::class)->create(['first_name' => 'Jane', 'last_name' => 'Doe']);
        $taxon = factory(Taxon::class)->create();

        Passport::actingAs($user);

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => $taxon->id,
            'identified_by_id' => $anotherUser->id,
            'identifier' => 'Some other name',
        ]));

        $response->assertCreated();

        $fieldObservation = FieldObservation::latest()->first();
        $this->assertTrue($fieldObservation->identifiedBy->is($anotherUser));
        $this->assertEquals($fieldObservation->identifier, 'Jane Doe');
    }

    /** @test */
    public function unless_identifier_is_provided_user_will_be_assigned_as_identifier_if_the_observation_has_some_identification()
    {
        $user = factory(User::class)->create();
        $taxon = factory(Taxon::class)->create();

        Passport::actingAs($user);

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => $taxon->id,
            'identified_by_id' => null,
            'identifier' => null,
        ]));

        $response->assertStatus(201);

        $fieldObservation = FieldObservation::latest()->first();
        $this->assertTrue($fieldObservation->identifiedBy->is($user));
        $this->assertEquals($fieldObservation->identifier, $user->full_name);
    }

    // /** @test */
    // public function curators_are_notified_of_new_field_observation()
    // {
    //     $this->seed('RolesTableSeeder');
    //
    //     $taxon = factory(Taxon::class)->create(['name' => 'Cerambyx cerdo']);
    //     $curator = factory(User::class)->create()->assignRoles('curator');
    //     $taxon->curators()->attach($curator);
    //
    //     Passport::actingAs(factory(User::class)->create());
    //     $this->postJson('/api/field-observations', $this->validParams([
    //         'taxon_id' => $taxon->id,
    //     ]))->assertCreated();
    //
    //     Notification::assertSentTo($curator, FieldObservationForApproval::class, function ($notification) {
    //         return $notification->fieldObservation->is(FieldObservation::latest()->first());
    //     });
    // }
}
