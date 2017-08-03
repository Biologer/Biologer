<?php

namespace Tests\Feature\Contributor;

use App\User;
use App\Photo;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Observation;
use App\FieldObservation;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddFieldObservationTest extends TestCase
{
    use DatabaseMigrations;

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
        $this->withExceptionHandling();
        $fieldObservationsCount = FieldObservation::count();
        $observationsCount = Observation::count();

        $response = $this->post(
            '/contributor/field-observations', $this->validParams()
        );

        $response->assertRedirect('/login');
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());
        $this->assertEquals($observationsCount, Observation::count());
    }

    /** @test */
    function authenticated_user_can_add_field_observation()
    {
        $user = factory(User::class)->create();

        $fieldObservationsCount = FieldObservation::count();

        $response = $this->actingAs($user)->post(
            '/contributor/field-observations', $this->validParams()
        );

        $response->assertRedirect('/contributor/field-observations');

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
    function year_is_required_when_adding_field_observation()
    {
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->actingAs(factory(User::class)->make())
            ->withExceptionHandling()->post(
            '/contributor/field-observations', $this->validParams([
                'year' => '',
            ])
        );

        $response->assertRedirect();
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());

        $response->assertSessionHasErrors('year');
    }

    /** @test */
    function year_must_be_valid_year()
    {
        $response = $this->actingAs(factory(User::class)->make())
            ->withExceptionHandling()->post(
            '/contributor/field-observations', $this->validParams([
                'year' => '1aa2',
            ])
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors('year');
    }

    /** @test */
    function year_cannot_be_in_the_future()
    {
        $response = $this->actingAs(factory(User::class)->make())
            ->withExceptionHandling()->post(
            '/contributor/field-observations', $this->validParams([
                'year' => date('Y') + 1,
            ])
        );

        $response->assertRedirect();
        $response->assertSessionHasErrors('year');
    }

    /** @test */
    function latitude_is_required_when_adding_field_observation()
    {
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->actingAs(factory(User::class)->make())
            ->withExceptionHandling()->post(
            '/contributor/field-observations', $this->validParams([
                'latitude' => '',
            ])
        );

        $response->assertRedirect();
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());

        $response->assertSessionHasErrors('latitude');
    }

    /** @test */
    function longitude_is_required_when_adding_field_observation()
    {
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->actingAs(factory(User::class)->make())
            ->withExceptionHandling()->post(
            '/contributor/field-observations', $this->validParams([
                'longitude' => '',
            ])
        );

        $response->assertRedirect();
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());

        $response->assertSessionHasErrors('longitude');
    }

    /** @test */
    function altitude_is_required_when_adding_field_observation()
    {
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->actingAs(factory(User::class)->make())
            ->withExceptionHandling()->post(
            '/contributor/field-observations', $this->validParams([
                'altitude' => '',
            ])
        );

        $response->assertRedirect();
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());

        $response->assertSessionHasErrors('altitude');
    }

    /** @test */
    function accuracy_is_required_when_adding_field_observation()
    {
        $fieldObservationsCount = FieldObservation::count();

        $response = $this->actingAs(factory(User::class)->make())
            ->withExceptionHandling()->post(
            '/contributor/field-observations', $this->validParams([
                'accuracy' => '',
            ])
        );

        $response->assertRedirect();
        $this->assertEquals($fieldObservationsCount, FieldObservation::count());

        $response->assertSessionHasErrors('accuracy');
    }

    /** @test */
    function mgrs_field_is_calculated_automaticaly()
    {
        $this->actingAs(factory(User::class)->create())->post(
            '/contributor/field-observations', $this->validParams([
                'latitude' => '43.60599592',
                'longitude' => '21.30373179',
            ])
        );

        tap(FieldObservation::first()->observation, function ($observation) {
            $this->assertEquals('34TEP22', $observation->mgrs10k);
        });
    }

    /** @test */
    function add_comment_along_with_observation()
    {
        $this->actingAs(factory(User::class)->create())->post(
            '/contributor/field-observations', $this->validParams([
                'comment' => 'Some comment',
            ])
        );

        tap(FieldObservation::first()->comments, function ($comments) {
            $this->assertCount(1, $comments);
            $this->assertEquals('Some comment', $comments->first()->body);
        });
    }

    /** @test */
    function photo_can_be_saved_with_observation()
    {
        config(['alciphron.photos_per_observation' => 3]);

        Storage::fake('public');
        $photosCount = Photo::count();

        $request = $this->actingAs(factory(User::class)->create())->post(
            '/contributor/field-observations', $this->validParams([
                'source' => 'John Doe',
                'photos' => [
                    File::image('test-image.jpg')->storeAs("uploads", 'test-image.jpg', 'public'),
                ],
            ])
        );

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

        Storage::fake('public');
        $photosCount = Photo::count();

        $response = $this->withExceptionHandling()->actingAs(factory(User::class)->create())->post(
            '/contributor/field-observations', $this->validParams([
                'photos' => [
                    File::image('test-image1.jpg')->storeAs("uploads", 'test-image1.jpg', 'public'),
                    File::image('test-image2.jpg')->storeAs("uploads", 'test-image2.jpg', 'public'),
                    File::image('test-image3.jpg')->storeAs("uploads", 'test-image3.jpg', 'public'),
                    File::image('test-image4.jpg')->storeAs("uploads", 'test-image4.jpg', 'public'),
                ],
            ])
        );

        $response->assertRedirect();
        $this->assertEquals($photosCount, Photo::count());
    }

    /** @test */
    function photos_can_be_null()
    {
        $response = $this->withExceptionHandling()->actingAs(factory(User::class)->create())->post(
            '/contributor/field-observations', $this->validParams([
                'photos' => null,
            ])
        );

        $response->assertRedirect('/contributor/field-observations');
    }
}
