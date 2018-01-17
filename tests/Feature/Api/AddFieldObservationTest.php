<?php

namespace Tests\Feature\Api;

use App\User;
use App\Photo;
use App\Taxon;
use Carbon\Carbon;
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
        ], $overrides);
    }

    /** @test */
    function guests_cannot_add_new_field_observations()
    {
        $fieldObservationsCount = FieldObservation::count();
        $observationsCount = Observation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams());

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

        $response = $this->postJson('/api/field-observations', $this->validParams());

        $response->assertStatus(201);

        $this->assertEquals($fieldObservationsCount + 1, FieldObservation::count());

        tap(FieldObservation::latest()->first()->observation, function ($observation) use ($user) {
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
        });
    }

    /** @test */
    function users_full_name_as_source_when_source_is_not_provided()
    {
        $this->withoutExceptionHandling();
        Passport::actingAs(factory(User::class)->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]));

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'source' => '',
        ]));

        $response->assertStatus(201);
        $fieldObservation = FieldObservation::latest()->first();
        $this->assertEquals('Jane Doe', $fieldObservation->observation->observer);
    }

    /** @test */
    function year_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'year' => null,
        ]));

        $response->assertValidationErrors('year');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function year_must_be_valid_year()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'year' => '1aa2',
        ]));

        $response->assertValidationErrors('year');
    }

    /** @test */
    function year_cannot_be_in_the_future()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->postJson('api/field-observations', $this->validParams([
            'year' => date('Y') + 1,
        ]));

        $response->assertValidationErrors('year');
    }

    /** @test */
    function month_cannot_be_in_the_future()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->postJson('api/field-observations', $this->validParams([
            'year' => date('Y'),
            'month' => date('m') + 1,
        ]));

        $response->assertValidationErrors('month');
    }

    /** @test */
    function month_cannot_be_string()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->postJson('api/field-observations', $this->validParams([
            'year' => date('Y'),
            'month' => 'invalid',
        ]));

        $response->assertValidationErrors('month');
    }

    /** @test */
    function month_cannot_be_negative_number()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->postJson('api/field-observations', $this->validParams([
            'year' => date('Y'),
            'month' => '-1',
        ]));

        $response->assertValidationErrors('month');
    }

    /** @test */
    function day_cannot_be_in_the_future_longer_than_a_day()
    {
        Passport::actingAs(factory(User::class)->make());

        $now = Carbon::now();

        $response = $this->postJson('api/field-observations', $this->validParams([
            'year' => $now->year,
            'month' => $now->month,
            'day' => $now->day + 2,
        ]));

        $response->assertValidationErrors('day');
    }

    /** @test */
    function day_cannot_be_negative_number()
    {
        Passport::actingAs(factory(User::class)->make());

        $now = Carbon::now();

        $response = $this->postJson('api/field-observations', $this->validParams([
            'year' => $now->year,
            'month' => $now->month,
            'day' => '-1',
        ]));

        $response->assertValidationErrors('day');
    }

    /** @test */
    function day_cannot_be_string()
    {
        Passport::actingAs(factory(User::class)->make());

        $now = Carbon::now();

        $response = $this->postJson('api/field-observations', $this->validParams([
            'year' => $now->year,
            'month' => $now->month,
            'day' => 'invalid',
        ]));

        $response->assertValidationErrors('day');
    }

    /** @test */
    function latitude_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => null,
        ]));

        $response->assertValidationErrors('latitude');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function latitude_must_be_number_less_than_90()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => '91',
        ]));

        $response->assertValidationErrors('latitude');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function latitude_must_be_number_breate_than_negative_90()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => '-91',
        ]));

        $response->assertValidationErrors('latitude');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function longitude_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'longitude' => null,
        ]));

        $response->assertValidationErrors('longitude');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function longitude_must_be_number_less_than_180()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'longitude' => '181',
        ]));

        $response->assertValidationErrors('longitude');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function longitude_must_be_number_greater_than_negative_180()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'longitude' => '-181',
        ]));

        $response->assertValidationErrors('longitude');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function elevation_is_required_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'elevation' => null,
        ]));

        $response->assertValidationErrors('elevation');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function elevation_must_be_number()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'elevation' => 'aaa',
        ]));

        $response->assertValidationErrors('elevation');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function accuracy_is_optional_when_adding_field_observation()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'accuracy' => null,
        ]));

        $response->assertSuccessful();
        $this->assertEquals($fieldObservationsCount + 1, FieldObservation::count());
    }

    /** @test */
    function accuracy_must_be_number()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'accuracy' => 'aaa',
        ]));

        $response->assertValidationErrors('accuracy');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function taxon_is_optional()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => null,
        ]));

        $response->assertStatus(201);
        $this->assertEquals($fieldObservationsCount + 1, FieldObservation::count());
    }

    /** @test */
    function fails_if_taxon_does_not_exist()
    {
        Passport::actingAs(factory(User::class)->make());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => '999999999',
        ]));

        $response->assertValidationErrors('taxon_id');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }

    /** @test */
    function observation_is_stored_with_correct_taxon()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();
        $taxon = factory(Taxon::class)->create();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'taxon_id' => $taxon->id,
        ]));

        $response->assertStatus(201);
        $this->assertEquals($fieldObservationsCount + 1, FieldObservation::count());
        $this->assertEquals($taxon->id, Observation::first()->taxon_id);
    }

    /** @test */
    function taxon_suggestion_is_stored()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'taxon_suggestion' => 'Cerambyx cerdo',
        ]));

        $response->assertStatus(201);
        $this->assertEquals($fieldObservationsCount + 1, FieldObservation::count());
        $this->assertEquals('Cerambyx cerdo', FieldObservation::latest()->first()->taxon_suggestion);
    }

    /** @test */
    function mgrs_field_is_calculated_automaticaly()
    {
        Passport::actingAs(factory(User::class)->create());
        $response = $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => '43.60599592',
            'longitude' => '21.30373179',
        ]));

        $response->assertStatus(201);
        tap(FieldObservation::first()->observation, function ($observation) {
            $this->assertEquals('34TEP22', $observation->mgrs10k);
        });
    }

    /** @test */
    function mgrs_field_cannot_be_calculated_in_polar_region()
    {
        Passport::actingAs(factory(User::class)->create());
        $response = $this->postJson('/api/field-observations', $this->validParams([
            'latitude' => '85.0',
            'longitude' => '21.30373179',
        ]));

        $response->assertStatus(201);
        tap(FieldObservation::first()->observation, function ($observation) {
            $this->assertNull($observation->mgrs10k);
        });
    }

    /** @test */
    function photo_can_be_saved_with_observation()
    {
        $this->withoutExceptionHandling();
        config(['alciphron.photos_per_observation' => 3]);

        Passport::actingAs($user = factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]));
        Storage::fake('public');
        $photosCount = Photo::count();
        File::image('test-image.jpg')->storeAs("uploads/{$user->id}", 'test-image.jpg', 'public');

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'observer' => 'John Doe',
            'photos' => [
                'test-image.jpg',
            ],
        ]));

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
        File::image('test-image1.jpg')->storeAs("uploads", 'test-image1.jpg', 'public');
        File::image('test-image2.jpg')->storeAs("uploads", 'test-image2.jpg', 'public');
        File::image('test-image3.jpg')->storeAs("uploads", 'test-image3.jpg', 'public');
        File::image('test-image4.jpg')->storeAs("uploads", 'test-image4.jpg', 'public');

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'photos' => [
                'test-image1.jpg',
                'test-image2.jpg',
                'test-image3.jpg',
                'test-image4.jpg',
            ],
        ]));

        $response->assertValidationErrors('photos');
        $this->assertEquals($photosCount, Photo::count());
    }

    /** @test */
    function photos_can_be_null()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'photos' => null,
        ]));

        $response->assertStatus(201);
    }

    /** @test */
    function sex_is_optional()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'sex' => null,
        ]));

        $response->assertStatus(201);
    }

    /** @test */
    function sex_can_be_one_of_available_values()
    {
        Passport::actingAs(factory(User::class)->create());
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->postJson('/api/field-observations', $this->validParams([
            'sex' => 'invalid'
        ]));

        $response->assertValidationErrors('sex');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
    }
}
