<?php

namespace Tests\Unit;

use App\Models\Body;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_get_post(): void
    {
        $response = $this->get('/api/posts');

        $response->assertStatus(200);
    }

    public function test_post_post(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('post.jpg');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/posts', [
            'title' => 'Test title',
            'content' => 'Test content',
            'body_id' => Body::factory()->create()->id,
            'images' => [$image],
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('posts', [
            'title' => 'Test title',
            'content' => 'Test content',
        ]);
        Storage::disk('public')->assertExists('images/' . $image->hashName());
    }
}
