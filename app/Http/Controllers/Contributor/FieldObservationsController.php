<?php

namespace App\Http\Controllers\Contributor;

use App\Comment;
use App\FieldObservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FieldObservationsController extends Controller
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
        $this->validate($request, [
            'year' => 'required|date_format:Y|before_or_equal:now',
            'latitude' => 'required',
            'longitude'=> 'required',
            'altitude'=> 'required',
            'accuracy' => 'required',
            'source' => 'nullable',
            'photos' => 'nullable|array|max:'.config('alciphron.photos_per_observation'),
        ]);

        $observation = $this->createObservation($request->all());

        if ($comment = trim($request->input('comment'))) {
            $observation->addNewComment($comment);
        }

        $observation->details->addPhotos($request->input('photos', []));

        return redirect('/contributor/field-observations');
    }

    /**
     * Create observation.
     *
     * @param  array  $data
     * @return \App\Observation
     */
    protected function createObservation($data)
    {
        return FieldObservation::create([
            'source' => $data['source'],
        ])->observation()->create([
            'year' => $data['year'],
            'month' => $data['month'],
            'day' => $data['day'],
            'location' => $data['location'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'mgrs10k' => mgrs10k($data['latitude'], $data['longitude']),
            'accuracy' => $data['accuracy'],
            'altitude' => $data['altitude'],
            'created_by_id' => auth()->user()->id,
        ]);
    }
}
