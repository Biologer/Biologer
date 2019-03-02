<?php

namespace App\Http\Controllers\Api;

use App\DEM\ReaderInterface;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ElevationController extends Controller
{
    public function __invoke(Request $request, ReaderInterface $reader)
    {
        $data = $this->validate($request, [
            'latitude' => ['required', 'numeric', 'between:-60,60'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

       return [
           'elevation' => $reader->getElevation($data['latitude'], $data['longitude'])
       ];
    }
}
