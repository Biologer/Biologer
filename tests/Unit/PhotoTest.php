<?php

namespace Tests\Unit;

use App\Photo;
use App\License;
use Tests\TestCase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;

class PhotoTest extends TestCase
{
    /** @test */
    public function files_are_removed_when_photo_is_deleted()
    {
        Storage::fake('public');
        $file = File::image('example.jpg')->storeAs('uploads', 'example.jpg', [
            'disk' => 'public',
        ]);

        $photo = Photo::store($file, [
            'author' => 'John Doe',
            'license' => License::firstId(),
        ]);

        Storage::disk('public')->assertExists($photo->path);

        $photo->delete();

        Storage::disk('public')->assertMissing($photo->path);
    }
}
