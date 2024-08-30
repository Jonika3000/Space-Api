<?php

namespace Tests\Unit;

use App\Models\Body;
use App\Models\Post;
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

    public function test_create_post(): void
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

    public function test_update_post_non_author(): void
    {
        Storage::fake('public');
        $post = Post::factory()->create();
        $image = UploadedFile::fake()->image('post.jpg');
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->put('/api/posts/'.$post->id, [
            'title' => 'Tes1 title',
            'content' => 'Test1 content',
            'body_id' => Body::factory()->create()->id,
            'images' => [$image],
        ]);

        $response->assertStatus(403);
    }

    public function test_update_post_author(): void
    {
        Storage::fake('public');
        $image = UploadedFile::fake()->image('post.jpg');
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->put('/api/posts/'.$post->id, [
            'title' => 'Tes1 title',
            'content' => 'Test1 content',
            'body_id' => Body::factory()->create()->id,
            'images' => [$image],
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Tes1 title',
            'content' => 'Test1 content',
        ]);
        Storage::disk('public')->assertExists('images/' . $image->hashName());
    }

    public function test_show_post(): void
    {
        $post = Post::factory()->create();
        $response = $this->get('/api/posts/'.$post->id);

        $response->assertStatus(200);
    }

    public function test_delete_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->delete('/api/posts/'.$post->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_delete_non_author_post(): void
    {
        $user = User::factory()->create(['role'=>'user']);
        $post = Post::factory()->create();
        $response = $this->actingAs($user)->delete('/api/posts/'.$post->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
        ]);
    }

    public function test_by_user_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $response = $this->get('api/posts/user/'.$user->id);

        $response->assertStatus(200);
        $response->assertSee($post->id);
    }

    public function test_admin_delete_random_post(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($user)->delete('/api/posts/'.$post->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
