<?php

namespace Tests\Unit;

use App\Enums\BodiesTypeEnum;
use App\Models\Body;
use App\Models\Galaxy;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BodyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_get_bodies(): void
    {
        $response = $this->get('api/bodies');

        $response->assertStatus(200);
    }

    public function test_create_bodies_non_admin_user(): void
    {
        $user = User::factory()->create(['role'=>'user']);
        $image = UploadedFile::fake()->image('image.jpg');

        $response = $this->actingAs($user)->post('/api/bodies', [
            'title' => 'Test title',
            'type' => fake()->randomElement(BodiesTypeEnum::cases())->value,
            'description' => 'Test description',
            'image' => $image,
            'galaxy_id' => Galaxy::factory()->create()->id
        ]);

        $response->assertStatus(403);
    }

    public function test_create_bodies_admin_user(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'admin']);
        $image = UploadedFile::fake()->image('image.jpg');

        $response = $this->actingAs($user)->post('/api/bodies', [
            'title' => 'Test title',
            'type' => fake()->randomElement(BodiesTypeEnum::cases())->value,
            'description' => 'Test description',
            'image' => $image,
            'galaxy_id' => Galaxy::factory()->create()->id
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('bodies', [
            'title' => 'Test title',
        ]);
        Storage::disk('public')->assertExists('bodies/' . $image->hashName());
    }

    public function test_show_body(): void
    {
        $body = Body::factory()->create();
        $response = $this->get('/api/bodies/' . $body->id);

        $response->assertStatus(200);
        $response->assertSee($body->id);
    }

    public function test_update_body(): void
    {
        $image = UploadedFile::fake()->image('body.jpg');
        $user = User::factory()->create(['role' => 'admin']);
        $body = Body::factory()->create();
        $response = $this->actingAs($user)->put('/api/bodies/'.$body->id, [
            'title' => 'Test update title',
            'type' => fake()->randomElement(BodiesTypeEnum::cases())->value,
            'description' => 'Test update description',
            'image' => $image,
            'galaxy_id' => Galaxy::factory()->create()->id
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('bodies', [
            'id' => $body->id,
            'title' => 'Test update title',
            'description' => 'Test update description',
        ]);
    }

    public function test_delete_non_admin_body(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $body = Body::factory()->create();

        $response = $this->actingAs($user)->delete('/api/bodies/'.$body->id);
        $response->assertStatus(403);
        $this->assertDatabaseHas('bodies', [
            'id' => $body->id,
        ]);
    }

    public function test_delete_admin_body(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $body = Body::factory()->create();

        $response = $this->actingAs($user)->delete('/api/bodies/'.$body->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('bodies', [
            'id' => $body->id,
        ]);
    }
}
