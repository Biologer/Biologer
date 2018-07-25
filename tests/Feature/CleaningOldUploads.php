<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;

class CleaningOldUploads extends TestCase
{
    /** @test */
    public function can_remove_old_uploaded_files()
    {
        Storage::fake('public');
        $path = Storage::disk('public')->put('uploads', File::image('test.jpg'));

        Storage::disk('public')->assertExists($path);

        Carbon::setTestNow(Carbon::tomorrow()->addDay());
        $this->artisan('uploads:clean');

        Storage::disk('public')->assertMissing($path);
    }
}
