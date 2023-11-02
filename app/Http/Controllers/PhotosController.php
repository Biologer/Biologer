<?php

namespace App\Http\Controllers;

use App\ImageLicense;
use App\Photo;
use Illuminate\Support\Facades\Auth;

class PhotosController extends Controller
{
    public function file(Photo $photo)
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('api')->user();

        $this->authorizeForUser($user, 'viewOriginal', $photo);

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
