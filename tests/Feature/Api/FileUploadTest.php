<?php

namespace Tests\Feature\Api;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FileUploadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function authenticated_user_can_upload_file()
    {
        Storage::fake('public');
        Passport::actingAs($user = factory(User::class)->create());

        $response = $this->json('POST', '/api/uploads', [
            'file' => $file = File::image('test-image.jpg', 800, 600)->size(200),
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('path', $response->json());
        $this->assertEquals("uploads/{$user->id}/{$file->hashName()}", $response->json()['path']);
        Storage::disk('public')->assertExists($response->json()['path']);
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
}
