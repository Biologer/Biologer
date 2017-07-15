<?php

namespace Tests\Feature\Contributor;

use App\User;
use Tests\TestCase;
use App\Observation;
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
            'latitude' => 45.251667,
            'longitude' => 19.836944,
            'accuracy' => 100,
            'altitude' => 80,
        ], $overrides);
    }
    /** @test */
    function authenticated_user_can_add_field_observation()
    {
        $user = factory(User::class)->create();

        $this->assertSame(0, Observation::count());

        $response = $this->actingAs($user)->post(
            '/contributor/field-observations', $this->validParams()
        );

        $response->assertRedirect('/contributor/field-observations');

        $this->assertCount(1, $observations = Observation::all());
        tap($observations->first(), function ($observation) use ($user) {
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
    function guests_cannot_add_new_field_observations()
    {
        $this->withExceptionHandling();

        $response = $this->post(
            '/contributor/field-observations', $this->validParams()
        );

        $response->assertRedirect('/login');
        $this->assertEquals(0, Observation::count());
    }

    /** @test */
    function mgrs_field_is_calculated_automaticaly()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post(
            '/contributor/field-observations', $this->validParams()
        );

        tap(Observation::first(), function ($observation) {
            // TODO: Calculate MGRS
            // $this->assertSame('SOME MGRS', $observation->mgrs_field);
        });
    }
}
