<?php

namespace Tests\Feature\Api;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function authenticated_user_can_upload_file()
    {
        Storage::fake('public');
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->json('POST', '/api/uploads', [
            'file' => $file = File::image('test-image.jpg', 800, 600)->size(200),
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('file', $response->json());
        $this->assertEquals($file->hashName(), $response->json()['file']);
        Storage::disk('public')->assertExists("uploads/{$user->id}/{$response->json()['file']}");
    }

    /** @test */
    function unauthenticated_user_cannot_upload_file()
    {
        $response = $this->withExceptionHandling()->json('POST', '/api/uploads', [
            'file' => File::image('test-image.jpg', 800, 600)->size(200),
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    function file_is_required()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->withExceptionHandling()->json('POST', '/api/uploads', []);

        $response->assertStatus(422);
    }

    /** @test */
    function uploaded_file_must_be_image()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->withExceptionHandling()->json('POST', '/api/uploads', [
            'file' => File::create('test-document.pdf', 200),
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    function image_cannot_be_wider_than_800px()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->withExceptionHandling()->json('POST', '/api/uploads', [
            'file' => File::image('test-image.jpg', 801, 600),
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    function image_cannot_be_higher_than_800px()
    {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->withExceptionHandling()->json('POST', '/api/uploads', [
            'file' => File::image('test-image.jpg', 600, 801),
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    function authenticated_user_can_remove_own_files()
    {
        Storage::fake('public');
        Passport::actingAs($user = factory(User::class)->make());
        Storage::disk('public')->putFileAs(
            'uploads/'.$user->id,
            File::image('test-image.jpg', 800, 600)->size(200),
            'test-image.jpg'
        );

        Storage::disk('public')->assertExists("uploads/{$user->id}/test-image.jpg");

        $response = $this->withExceptionHandling()->json('DELETE', '/api/uploads', [
            'file' => 'test-image.jpg',
        ]);

        $response->assertStatus(204);
        Storage::disk('public')->assertMissing("uploads/{$user->id}/test-image.jpg");
    }

    /** @test */
    function user_cannot_remove_files_owned_by_others()
    {
        Storage::fake('public');
        $owner = factory(User::class)->create();
        Storage::disk('public')->putFileAs(
            'uploads/'.$owner->id,
            File::image('test-image.jpg', 800, 600)->size(200),
            'test-image.jpg'
        );

        Passport::actingAs(factory(User::class)->make());
        $response = $this->withExceptionHandling()->json('DELETE', '/api/uploads', [
            'file' => 'test-image.jpg',
        ]);

        $response->assertStatus(204);
        Storage::disk('public')->assertExists("uploads/{$owner->id}/test-image.jpg");
    }

}