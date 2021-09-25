<?php

namespace Tests\Feature\Api;

use App\AtlasCode;
use App\FieldObservation;
use App\Jobs\ProcessUploadedPhoto;
use App\Notifications\FieldObservationForApproval;
use App\Observation;
use App\Photo;
use App\Taxon;
use App\User;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddFieldObservationTest extends TestCase
{
    protected function setUp(): void
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
            'habitat' => 'Swamp',
            'found_on' => 'Leaf of birch',
            'atlas_code' => AtlasCode::CODES[0],
        ], $overrides);
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

    private function createAuthenticatedUser($data = [])
    {
        return $this->setTestClientMock(Passport::actingAs(User::factory()->create($data)));
    }

    private function makeAuthenticatedUser($data = [])
    {
        return $this->setTestClientMock(Passport::actingAs(User::factory()->make($data)));
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
        $this->handleValidationExceptions();
        $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
        $user = $this->createAuthenticatedUser();

        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => $taxon->id,
        ]));

        $response->assertCreated();

        FieldObservation::assertCount($fieldObservationsCount + 1);
        $fieldObservation = FieldObservation::latest()->first();

        $this->assertEquals('12:00', $fieldObservation->time->format('H:i'));
        $this->assertEquals('Cerambyx cerdo', $fieldObservation->taxon_suggestion);
        $this->assertEquals(AtlasCode::CODES[0], $fieldObservation->atlas_code);

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
            $this->assertEquals('Swamp', $observation->habitat);
            $this->assertEquals('Leaf of birch', $observation->found_on);
            $this->assertEquals('Cerambyx cerdo', $observation->original_identification);
            $this->assertEquals('Test Client App', $observation->client_name);
        });
    }

    /** @test */
    public function activity_log_entry_is_added_when_field_observation_is_added()
    {
        $user = $this->createAuthenticatedUser();
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
        $this->createAuthenticatedUser([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);

        $this->postJson('/api/field-observations', $this->validParams([
            'source' => '',
        ]))->assertCreated();

        $this->assertEquals('Jane Doe', FieldObservation::latest()->first()->observation->observer);
    }

    /**
     * @test
     * @dataProvider invalidYearData
     *
     * @param mixed $year
     */
    public function year_is_validated($year)
    {
        $this->makeAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'year' => null,
        ]))->assertJsonValidationErrors('year');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    public function invalidYearData()
    {
        return [
            'Cannot be null' => [null],
            'Cannot be empty string' => [''],
            'Cannot be random string' => ['1aa2'],
            'Cannot be in the future' => [date('Y') + 1],
        ];
    }

    /**
     * @test
     * @dataProvider invalidMonthData
     *
     * @param mixed $month
     */
    public function month_is_validated($month)
    {
        $this->makeAuthenticatedUser();

        $this->postJson('api/field-observations', $this->validParams([
            'year' => date('Y'),
            'month' => $month,
        ]))->assertJsonValidationErrors('month');
    }

    public function invalidMonthData()
    {
        return [
            'Cannot be in the future' => [date('m') + 1],
            'Cannot be random string' => ['invalid'],
            'Cannot be negative number' => [-1],
            'Cannot be negative zero' => [0],
            'Cannot be greater than 12' => [13],
        ];
    }

    /**
     * @test
     * @dataProvider invalidDayData
     *
     * @param mixed $day
     */
    public function day_is_validated($day)
    {
        $this->makeAuthenticatedUser();

        $now = Carbon::now();

        $this->postJson('api/field-observations', $this->validParams([
            'year' => $now->year,
            'month' => $now->month,
            'day' => $day,
        ]))->assertJsonValidationErrors('day');
    }

    public function invalidDayData()
    {
        return [
            'Cannot be in the future longer than a day' => [now()->day + 2],
            'Cannot be negative zero' => [0],
            'Cannot be negative number' => [-1],
            'Cannot be random string' => ['invalid'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidLatitudeData
     *
     * @param mixed $latitude
     */
    public function latitude_is_validated($latitude)
    {
        $this->makeAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => $latitude,
        ]))->assertJsonValidationErrors('latitude');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    public function invalidLatitudeData()
    {
        return [
            'Cannot be null' => [null],
            'Cannot be empty string' => [''],
            'Cannot be random string' => ['wjeafn'],
            'Cannot be number less than -90' => [-91],
            'Cannot be number greater than 90' => [91],
        ];
    }

    /**
     * @test
     * @dataProvider invalidLongitudeData
     *
     * @param mixed $longitude
     */
    public function longitude_is_validated($longitude)
    {
        $this->makeAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'longitude' => $longitude,
        ]))->assertJsonValidationErrors('longitude');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    public function invalidLongitudeData()
    {
        return [
            'Cannot be empty' => [null],
            'Cannot be string' => ['asdasd'],
            'Cannot be greater than 180' => [181],
            'Cannot be less than -180' => [-181],
        ];
    }

    /**
     * @test
     * @dataProvider invalidElevationData
     *
     * @param mixed $elevation
     */
    public function elevation_is_validated($elevation)
    {
        $this->makeAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'elevation' => $elevation,
        ]))->assertJsonValidationErrors('elevation');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    public function invalidElevationData()
    {
        return [
            'Cannot be null' => [null],
            'Cannot be empty string' => [''],
            'Cannot be sting' => ['aaa'],
        ];
    }

    /** @test */
    public function accuracy_is_optional_when_adding_field_observation()
    {
        $this->createAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'accuracy' => null,
        ]))->assertSuccessful();

        FieldObservation::assertCount($fieldObservationsCount + 1);
    }

    /** @test */
    public function accuracy_must_be_number()
    {
        $this->makeAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'accuracy' => 'aaa',
        ]))->assertJsonValidationErrors('accuracy');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function taxon_is_optional()
    {
        $this->createAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => null,
        ]))->assertCreated();

        FieldObservation::assertCount($fieldObservationsCount + 1);
    }

    /** @test */
    public function fails_if_taxon_does_not_exist()
    {
        $this->makeAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => '999999999',
        ]))->assertJsonValidationErrors('taxon_id');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function observation_is_stored_with_correct_taxon()
    {
        $this->createAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();
        $taxon = Taxon::factory()->create();

        $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => $taxon->id,
        ]))->assertCreated();

        FieldObservation::assertCount($fieldObservationsCount + 1);
        $this->assertEquals($taxon->id, Observation::first()->taxon_id);
    }

    /** @test */
    public function taxon_suggestion_is_stored()
    {
        $this->createAuthenticatedUser();
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
        $this->createAuthenticatedUser();

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
        $this->createAuthenticatedUser();

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

    /** @test */
    public function photos_are_queued_to_be_processed_if_needed()
    {
        config(['biologer.photo_resize_dimension' => 800]);

        Queue::fake();
        Storage::fake('public');

        $user = $this->createAuthenticatedUser();
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
        config(['biologer.photos_per_observation' => 3]);

        $this->makeAuthenticatedUser();
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
        ]))->assertJsonValidationErrors('photos');

        Photo::assertCount($photosCount);
    }

    /** @test */
    public function photos_can_be_null()
    {
        $this->createAuthenticatedUser();

        $this->postJson('/api/field-observations', $this->validParams([
            'photos' => null,
        ]))->assertCreated();
    }

    /** @test */
    public function sex_is_optional()
    {
        $this->createAuthenticatedUser();

        $this->postJson('/api/field-observations', $this->validParams([
            'sex' => null,
        ]))->assertCreated();
    }

    /** @test */
    public function sex_can_only_be_one_of_available_values()
    {
        $this->makeAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'sex' => 'invalid',
        ]))->assertJsonValidationErrors('sex');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function time_is_optional()
    {
        $this->createAuthenticatedUser();

        $this->postJson('/api/field-observations', $this->validParams([
            'time' => null,
        ]))->assertCreated();
    }

    /** @test */
    public function time_must_be_in_correct_format()
    {
        $this->makeAuthenticatedUser();
        $fieldObservationsCount = FieldObservation::count();

        $this->postJson('/api/field-observations', $this->validParams([
            'time' => 'invalid',
        ]))->assertJsonValidationErrors('time');

        FieldObservation::assertCount($fieldObservationsCount);
    }

    /** @test */
    public function admin_can_submit_observer_by_users_id()
    {
        $this->seed('RolesTableSeeder');
        $user = $this->createAuthenticatedUser()->assignRoles('admin');
        $anotherUser = User::factory()->create(['first_name' => 'Jane', 'last_name' => 'Doe']);

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'observed_by_id' => $anotherUser->id,
        ]));

        $response->assertCreated();

        $fieldObservation = FieldObservation::latest()->first();
        $this->assertTrue($fieldObservation->observedBy->is($anotherUser));
        $this->assertEquals($fieldObservation->observer, 'Jane Doe');
    }

    /** @test */
    public function admin_can_submit_observer_without_existing_user()
    {
        $this->seed('RolesTableSeeder');
        $user = $this->createAuthenticatedUser()->assignRoles('admin');

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'observed_by_id' => null,
            'observer' => 'Jane Doe',
        ]));

        $response->assertCreated();

        $fieldObservation = FieldObservation::latest()->first();
        $this->assertNull($fieldObservation->observed_by_id);
        $this->assertEquals($fieldObservation->observer, 'Jane Doe');
    }

    /** @test */
    public function admin_can_submit_identifier_by_users_id_if_there_is_identification()
    {
        $this->seed('RolesTableSeeder');
        $user = $this->createAuthenticatedUser()->assignRoles('admin');
        $anotherUser = User::factory()->create(['first_name' => 'Jane', 'last_name' => 'Doe']);
        $taxon = Taxon::factory()->create();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => $taxon->id,
            'identified_by_id' => $anotherUser->id,
            'identifier' => 'Some other name',
        ]));

        $response->assertCreated();

        $fieldObservation = FieldObservation::latest()->first();
        $this->assertTrue($fieldObservation->identifiedBy->is($anotherUser));
        $this->assertEquals($fieldObservation->identifier, 'Jane Doe');
        $this->assertEquals($fieldObservation->observation->identifier, 'Jane Doe');
    }

    /** @test */
    public function unless_identifier_is_provided_user_will_be_assigned_as_identifier_if_the_observation_has_some_identification()
    {
        $user = $this->createAuthenticatedUser();
        $taxon = Taxon::factory()->create();

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
    //     $taxon = Taxon::factory()->create(['name' => 'Cerambyx cerdo']);
    //     $curator = User::factory()->create()->assignRoles('curator');
    //     $taxon->curators()->attach($curator);
    //
    //     Passport::actingAs(User::factory()->create());
    //     $this->postJson('/api/field-observations', $this->validParams([
    //         'taxon_id' => $taxon->id,
    //     ]))->assertCreated();
    //
    //     Notification::assertSentTo($curator, FieldObservationForApproval::class, function ($notification) {
    //         return $notification->fieldObservation->is(FieldObservation::latest()->first());
    //     });
    // }
}
