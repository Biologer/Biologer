<?php

namespace App\Http\Controllers;

use App\ImageLicense;
use App\Models\Photo;

class PhotosController extends Controller
{
    public function file(Photo $photo)
    {
        $this->authorize('viewOriginal', $photo);

        return Photo::filesystem()->response($photo->finalPath());
    }

    public function public(Photo $photo)
    {
        abort_unless($photo->isVisibleToPublic(), 404);

        if (ImageLicense::PARTIALLY_OPEN === $photo->license) {
            return Photo::filesystem()->response($photo->watermarkedPath());
        }

        return Photo::filesystem()->response($photo->finalPath());
    }
}
