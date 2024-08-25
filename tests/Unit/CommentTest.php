<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_comments_get(): void
    {
        $post = Post::factory()->hasComments(1)->create();
        $response = $this->get('api/comments/post/'.$post->id);

        $response->assertStatus(200);
        $response->assertSee($post->comments()->first()->id);
    }

    public function test_comment_create_user_auth(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $response = $this->actingAs($user)->post('api/comments/', [
            'content' => 'test comment',
            'post_id' => $post->id
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('comments', [
            'content' => 'test comment',
        ]);
    }

    public function test_comment_update_non_author(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create();
        $response = $this->actingAs($user)->put('api/comments/' . $comment->id, [
            'content' => 'test comment',
            'post_id' => $post->id
        ]);
        $response->assertStatus(403);
    }

    public function test_comment_update_author(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->put('api/comments/' . $comment->id, [
            'content' => 'update test comment',
            'post_id' => $post->id
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('comments', [
            'content' => 'update test comment',
        ]);
    }

    public function test_comment_delete_non_author(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();
        $response = $this->actingAs($user)->delete('api/comments/' . $comment->id);
        $response->assertStatus(403);
    }

    public function test_comment_delete_author(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->delete('api/comments/' . $comment->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }
}
