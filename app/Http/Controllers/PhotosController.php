<?php

namespace App\Http\Controllers;

use App\Photo;

class PhotosController extends Controller
{
    public function file(Photo $photo)
    {
        $this->authorize('viewOriginal', $photo);

        return $photo->streamResponse();
    }
}
