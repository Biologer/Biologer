<?php

namespace App\Http\Controllers\Api;

use App\DEM\Reader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ElevationController extends Controller
{
    public function __invoke(Request $request, Reader $reader)
    {
        $data = $this->validate($request, [
            'latitude' => ['required', 'numeric', 'between:-60,60'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        return [
           'elevation' => $reader->getElevation($data['latitude'], $data['longitude']),
       ];
    }
}
