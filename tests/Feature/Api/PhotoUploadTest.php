<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use App\User;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PhotoUploadTest extends TestCase
{
    #[Test]
    public function authenticated_user_can_upload_photo(): void
    {
        Storage::fake('public');
        Passport::actingAs($user = User::factory()->create());

        $response = $this->postJson('/api/uploads/photos', [
            'file' => File::image('test-image.jpg', 800, 600)->size(200),
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('file', $response->json());
        Storage::disk('public')->assertExists("uploads/{$user->id}/{$response->json('file')}");
    }

    #[Test]
    public function unauthenticated_user_cannot_upload_photo(): void
    {
        $this->postJson('/api/uploads/photos', [
            'file' => File::image('test-image.jpg', 800, 600)->size(200),
        ])->assertUnauthorized();
    }

    #[Test]
    public function file_is_required(): void
    {
        Passport::actingAs(User::factory()->make());

        $this->postJson('/api/uploads/photos', [])->assertJsonValidationErrors('file');
    }

    #[Test]
    public function uploaded_file_must_be_image(): void
    {
        Passport::actingAs(User::factory()->make());

        $this->postJson('/api/uploads/photos', [
            'file' => File::create('test-document.pdf', 200),
        ])->assertJsonValidationErrors('file');
    }

    #[Test]
    public function image_cannot_be_larger_than_max_configured_size(): void
    {
        config(['biologer.max_upload_size' => 2048]);

        Passport::actingAs(User::factory()->make());

        $this->postJson('/api/uploads/photos', [
            'file' => File::image('test-image.jpg')->size(2 * 1024 + 1),
        ])->assertJsonValidationErrors('file');
    }

    #[Test]
    public function authenticated_user_can_remove_own_photos(): void
    {
        Storage::fake('public');
        Passport::actingAs($user = User::factory()->make());
        Storage::disk('public')->putFileAs(
            'uploads/'.$user->id,
            File::image('test-image.jpg', 800, 600)->size(200),
            'test-image.jpg'
        );

        Storage::disk('public')->assertExists("uploads/{$user->id}/test-image.jpg");

        $this->deleteJson('/api/uploads/photos/test-image.jpg')->assertStatus(204);

        Storage::disk('public')->assertMissing("uploads/{$user->id}/test-image.jpg");
    }

    #[Test]
    public function user_cannot_remove_photos_uploaded_by_others(): void
    {
        Storage::fake('public');
        Passport::actingAs(User::factory()->create());
        $owner = User::factory()->create();
        Storage::disk('public')->putFileAs(
            'uploads/'.$owner->id,
            File::image('test-image.jpg', 800, 600)->size(200),
            'test-image.jpg'
        );

        $this->deleteJson('/api/uploads/photos/test-image.jpg')->assertNotFound();

        Storage::disk('public')->assertExists("uploads/{$owner->id}/test-image.jpg");
    }
}
