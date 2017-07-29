<?php

namespace App\Http\Controllers\Contributor;

use App\Comment;
use App\Observation;
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
        $observation = $this->createObservation($request->all());

        if ($request->input('comment')) {
            $observation->addComment(Comment::make([
                'body' => $request->input('comment'),
            ]));
        }

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
