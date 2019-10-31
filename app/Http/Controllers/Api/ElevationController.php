<?php

namespace App\Http\Controllers\Api;

use App\DEM\Reader;
use Illuminate\Http\Request;

class ElevationController
{
    public function __invoke(Request $request, Reader $reader)
    {
        $data = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-60,60'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        return [
           'elevation' => $reader->getElevation($data['latitude'], $data['longitude']),
       ];
    }
}
