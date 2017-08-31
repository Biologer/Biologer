<?php

namespace Tests\Feature\Api;

use App\User;
use App\Photo;
use App\Taxon;
use Tests\TestCase;
use App\Observation;
use App\FieldObservation;
use Laravel\Passport\Passport;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddFieldObservationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Valid observation data.
     *
     * @param  array  $overrides
     * @return array
     */
    protected function validParams($overrides = [])
    {
        return array_merge([
            'year' => '2017',
            'month' => '07',
            'day' => '15',
            'location' => 'Novi Sad',
            'latitude' => '45.251667',
            'longitude' => '19.836944',
            'accuracy' => '100',
            'altitude' => '80',
            'source' => 'John Doe',
        ], $overrides);
    }

    /** @test */
    function guests_cannot_add_new_field_observations()
    {
        $fieldObservationsCount = FieldObservation::count();
        $observationsCount = Observation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams()
        );

        $response->assertStatus(401);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
        $this->assertEquals($observationsCount, Observation::count());
    }

    /** @test */
    function authenticated_user_can_add_field_observation()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $fieldObservationsCount = FieldObservation::count();

        $response = $this->json(
            'POST', '/api/field-observations', $this->validParams()
        );

        $response->assertStatus(201);

        $this->assertEquals($fieldObservationsCount + 1, FieldObservation::count());

        $fieldObservation = FieldObservation::latest()->first();
        $this->assertEquals('John Doe', $fieldObservation->source);

        tap($fieldObservation->observation, function ($observation) use ($user) {
            $this->assertEquals('2017', $observation->year);
            $this->assertEquals('07', $observation->month);
            $this->assertEquals('15', $observation->day);
            $this->assertEquals('Novi Sad', $observation->location);
            $this->assertEquals(45.251667, $observation->latitude);
            $this->assertEquals(19.836944, $observation->longitude);
            $this->assertEquals(100, $observation->accuracy);
            $this->assertEquals(80, $observation->altitude);
            $this->assertNull($observation->approved_at);
            $this->assertEquals($user->id, $observation->created_by_id);
        });
    }

    /** @test */
    function users_full_name_as_source_when_source_is_not_provided()
    {
        Passport::actingAs(factory(User::class)->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]));

        $response = $this->json(
            'POST', '/api/field-observations', $this->validParams([
                'source' => '',
            ])
        );

        $fieldObservation = FieldObservation::latest()->first();
        $this->assertEquals('Jane Doe', $fieldObservation->source);
    }

    /** @test */
    function year_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'year' => '',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('year', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function year_must_be_valid_year()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'year' => '1aa2',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('year', $response->json()['errors']);
    }

    /** @test */
    function year_cannot_be_in_the_future()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->withExceptionHandling()->json(
            'POST', 'api/field-observations', $this->validParams([
                'year' => date('Y') + 1,
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('year', $response->json()['errors']);
    }

    /** @test */
    function latitude_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'latitude' => '',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('latitude', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function latitude_must_be_number_less_than_90()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'latitude' => '91',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('latitude', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function latitude_must_be_number_breate_than_negative_90()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'latitude' => '-91',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('latitude', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function longitude_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'longitude' => '',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('longitude', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function longitude_must_be_number_less_than_180()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'longitude' => '181',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('longitude', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function longitude_must_be_number_greater_than_negative_180()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'longitude' => '-181',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('longitude', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function altitude_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'altitude' => '',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('altitude', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function altitude_must_be_number()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'altitude' => 'aaa',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('altitude', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function accuracy_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'accuracy' => '',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('accuracy', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function accuracy_must_be_number()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'accuracy' => 'aaa',
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('accuracy', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function taxon_is_optional()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'taxon_id' => null,
            ])
        );

        $response->assertStatus(201);
        $this->assertEquals($fieldObservationsCount + 1, FieldObservation::count());
    }

    /** @test */
    function fails_if_taxon_does_not_exist()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'taxon_id' => 999999999,
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('taxon_id', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function observation_is_stored_with_correct_taxon()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();
        $taxon = factory(Taxon::class)->create();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'taxon_id' => $taxon->id,
            ])
        );

        $response->assertStatus(201);
        $this->assertEquals($fieldObservationsCount + 1, FieldObservation::count());
        $this->assertEquals($taxon->id, Observation::first()->taxon_id);
    }

    /** @test */
    function mgrs_field_is_calculated_automaticaly()
    {
        Passport::actingAs(factory(User::class)->create());
        $response = $this->json(
            'POST', '/api/field-observations', $this->validParams([
                'latitude' => '43.60599592',
                'longitude' => '21.30373179',
            ])
        );

        $response->assertStatus(201);
        tap(FieldObservation::first()->observation, function ($observation) {
            $this->assertEquals('34TEP22', $observation->mgrs10k);
        });
    }

    /** @test */
    function mgrs_field_cannot_be_calculated_in_polar_region()
    {
        Passport::actingAs(factory(User::class)->create());
        $response = $this->json(
            'POST', '/api/field-observations', $this->validParams([
                'latitude' => '85.0',
                'longitude' => '21.30373179',
            ])
        );

        $response->assertStatus(201);
        tap(FieldObservation::first()->observation, function ($observation) {
            $this->assertNull($observation->mgrs10k);
        });
    }

    /** @test */
    function add_comment_along_with_observation()
    {
        Passport::actingAs($user = factory(User::class)->create());
        $response = $this->json(
            'POST', '/api/field-observations', $this->validParams([
                'comment' => 'Some comment',
            ])
        );

        $response->assertStatus(201);
        tap(FieldObservation::first()->comments, function ($comments) use ($user) {
            $this->assertCount(1, $comments);
            $this->assertEquals('Some comment', $comments->first()->body);
            $this->assertEquals($user->id, $comments->first()->user_id);
        });
    }

    /** @test */
    function photo_can_be_saved_with_observation()
    {
        config(['alciphron.photos_per_observation' => 3]);

        Passport::actingAs(factory(User::class)->create());
        Storage::fake('public');
        $photosCount = Photo::count();

        $response = $this->json(
            'POST', '/api/field-observations', $this->validParams([
                'source' => 'John Doe',
                'photos' => [
                    File::image('test-image.jpg')->storeAs("uploads", 'test-image.jpg', 'public'),
                ],
            ])
        );

        $response->assertStatus(201);
        $photo = Photo::latest()->first();
        $this->assertEquals($photosCount + 1, Photo::count());
        $this->assertEquals("photos/{$photo->id}/test-image.jpg", $photo->path);
        $this->assertNotEmpty($photo->url);
        $this->assertTrue(Storage::disk('public')->exists($photo->path));
        $this->assertEquals('John Doe', $photo->author);
    }

    /** @test */
    function maximum_number_of_photos_that_can_be_saved_with_observation_can_be_set()
    {
        config(['alciphron.photos_per_observation' => 3]);

        Passport::actingAs(factory(User::class)->make());
        Storage::fake('public');
        $photosCount = Photo::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'photos' => [
                    File::image('test-image1.jpg')->storeAs("uploads", 'test-image1.jpg', 'public'),
                    File::image('test-image2.jpg')->storeAs("uploads", 'test-image2.jpg', 'public'),
                    File::image('test-image3.jpg')->storeAs("uploads", 'test-image3.jpg', 'public'),
                    File::image('test-image4.jpg')->storeAs("uploads", 'test-image4.jpg', 'public'),
                ],
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('photos', $response->json()['errors']);
        $this->assertEquals($photosCount, Photo::count());
    }

    /** @test */
    function photos_can_be_null()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'photos' => null,
            ])
        );

        $response->assertStatus(201);
    }

    /** @test */
    function additional_fields_are_not_stored_if_not_provided()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'dynamic' => null,
            ])
        );

        $response->assertStatus(201);
        tap(FieldObservation::latest()->first(), function ($observation) {
            $this->assertEmpty($observation->dynamicFieldValues);
        });
    }

    /** @test */
    function gender_can_be_submitted_and_stored_as_dynamic_field()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'dynamic' => [
                    'gender' => 'male',
                ],
            ])
        );

        $response->assertStatus(201);
        tap(FieldObservation::latest()->first(), function ($observation) {
            $this->assertArrayHasKey(
                'gender',
                $observation->mappedDynamicFields(),
                'Maybe the field wasn\'t stored?'
            );
        });
    }

    /** @test */
    function validation_fails_if_invalid_value_is_provided_for_gender()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->withExceptionHandling()->json(
            'POST', '/api/field-observations', $this->validParams([
                'dynamic' => [
                    'gender' => 'invalid',
                ],
            ])
        );

        $response->assertStatus(422);
        $this->assertArrayHasKey('dynamic.gender', $response->json()['errors']);
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }
}
