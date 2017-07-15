<?php

namespace App\Http\Controllers\Contributor;

use App\Observation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ObservationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Add new observation.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->createObservation($request->all());

        return redirect('/contributor/field-observations');
    }

    /**
     * Create observation.
     *
     * @param  array  $data
     * @return Observation
     */
    protected function createObservation($data)
    {
        return Observation::create([
            'year' => $data['year'],
            'month' => $data['month'],
            'day' => $data['day'],
            'location' => $data['location'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'accuracy' => $data['accuracy'],
            'altitude' => $data['altitude'],
            'created_by_id' => auth()->user()->id,
        ]);
    }
}
