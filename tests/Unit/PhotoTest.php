<?php

namespace Tests\Unit;

use App\ImageLicense;
use App\Photo;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoTest extends TestCase
{
    /** @test */
    public function files_are_removed_when_photo_is_deleted()
    {
        $photosDisk = config('biologer.photos_disk');
        Storage::fake($photosDisk);

        Storage::fake('public');
        $file = File::image('example.jpg')->storeAs('uploads', 'example.jpg', [
            'disk' => 'public',
        ]);

        $photo = Photo::store($file, [
            'author' => 'John Doe',
            'license' => ImageLicense::firstId(),
        ]);

        Storage::disk($photosDisk)->assertExists($photo->path);

        $photo->delete();

        Storage::disk($photosDisk)->assertMissing($photo->path);
    }
}
