<?php

namespace Tests\Feature\Contributor;

use App\User;
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
}
