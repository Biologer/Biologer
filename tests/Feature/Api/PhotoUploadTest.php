<?php

namespace Tests\Feature\Api;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;

class PhotoUploadTest extends TestCase
{
    /** @test */
    public function authenticated_user_can_upload_photo()
    {
        Storage::fake('public');
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->postJson('/api/uploads/photos', [
            'file' => File::image('test-image.jpg', 800, 600)->size(200),
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('file', $response->json());
        Storage::disk('public')->assertExists("uploads/{$user->id}/{$response->json('file')}");
    }

    /** @test */
    public function unauthenticated_user_cannot_upload_photo()
    {
        $this->postJson('/api/uploads/photos', [
            'file' => File::image('test-image.jpg', 800, 600)->size(200),
        ])->assertUnauthorized();
    }

    /** @test */
    public function file_is_required()
    {
        Passport::actingAs(factory(User::class)->make());

        $this->postJson('/api/uploads/photos', [])->assertJsonValidationErrors('file');
    }

    /** @test */
    public function uploaded_file_must_be_image()
    {
        Passport::actingAs(factory(User::class)->make());

        $this->postJson('/api/uploads/photos', [
            'file' => File::create('test-document.pdf', 200),
        ])->assertJsonValidationErrors('file');
    }

    /** @test */
    public function image_cannot_be_larger_than_max_configured_size()
    {
        config(['biologer.max_upload_size' => 2048]);

        Passport::actingAs(factory(User::class)->make());

        $this->postJson('/api/uploads/photos', [
            'file' => File::image('test-image.jpg')->size(2 * 1024 + 1),
        ])->assertJsonValidationErrors('file');
    }

    /** @test */
    public function authenticated_user_can_remove_own_photos()
    {
        Storage::fake('public');
        Passport::actingAs($user = factory(User::class)->make());
        Storage::disk('public')->putFileAs(
            'uploads/'.$user->id,
            File::image('test-image.jpg', 800, 600)->size(200),
            'test-image.jpg'
        );

        Storage::disk('public')->assertExists("uploads/{$user->id}/test-image.jpg");

        $this->deleteJson('/api/uploads/photos/test-image.jpg')->assertStatus(204);

        Storage::disk('public')->assertMissing("uploads/{$user->id}/test-image.jpg");
    }

    /** @test */
    public function user_cannot_remove_photos_uploaded_by_others()
    {
        Storage::fake('public');
        Passport::actingAs(factory(User::class)->create());
        $owner = factory(User::class)->create();
        Storage::disk('public')->putFileAs(
            'uploads/'.$owner->id,
            File::image('test-image.jpg', 800, 600)->size(200),
            'test-image.jpg'
        );

        $this->deleteJson('/api/uploads/photos/test-image.jpg')->assertNotFound();

        Storage::disk('public')->assertExists("uploads/{$owner->id}/test-image.jpg");
    }
}
