<?php

namespace Tests\Unit;

use App\Photo;
use App\License;
use Tests\TestCase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function file_is_removed_when_photo_is_deleted()
    {
        Storage::fake('public');
        $file = File::image('example.jpg')->storeAs('uploads', 'example.jpg', [
            'disk' => 'public',
        ]);

        $photo = Photo::store($file, [
            'author' => 'John Doe',
            'license' => License::getFirstId(),
        ]);

        Storage::disk('public')->assertExists($photo->path);

        $photo->delete();

        Storage::disk('public')->assertMissing($photo->path);
    }
}
