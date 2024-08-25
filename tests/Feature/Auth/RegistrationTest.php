<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_can_register(): void
    {
        Storage::fake('public');

        $banner = UploadedFile::fake()->image('banner.jpg');
        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post('/register', [
            'login' => 'Test User',
            'email' => 'test@example.com',
            'birthday' => fake()->date('d-m-Y'),
            'banner' => $banner,
            'avatar' => $avatar,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertAuthenticated();
        Storage::disk('public')->assertExists('avatars/'. $avatar->hashName());
        Storage::disk('public')->assertExists('banners/'. $banner->hashName());
    }
}
