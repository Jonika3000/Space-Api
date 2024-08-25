<?php

namespace Tests\Unit;

use App\Models\Galaxy;
use App\Models\User;
use Tests\TestCase;

class GalaxyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_get_galaxies(): void
    {
        $response = $this->get('api/galaxies');

        $response->assertStatus(200);
    }

    public function test_create_galaxy_non_admin_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post('/api/galaxies', [
            'title' => 'Test Galaxy Title',
            'description' => 'Test Galaxy Description'
        ]);

        $response->assertStatus(403);
    }

    public function test_create_galaxy_admin_user(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post('/api/galaxies', [
            'title' => 'Test Galaxy Title',
            'description' => 'Test Galaxy Description'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('galaxies', [
            'title' => 'Test Galaxy Title',
        ]);
    }

    public function test_show_galaxy(): void
    {
        $galaxy = Galaxy::factory()->create();
        $response = $this->get('/api/galaxies/' . $galaxy->id);

        $response->assertStatus(200);
        $response->assertSee($galaxy->id);
    }

    public function test_delete_galaxy_non_admin_user(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $galaxy = Galaxy::factory()->create();

        $response = $this->actingAs($user)->delete('/api/galaxies/' . $galaxy->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('galaxies', [
            'id' => $galaxy->id,
        ]);
    }

    public function test_delete_galaxy_admin_user(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $galaxy = Galaxy::factory()->create();

        $response = $this->actingAs($user)->delete('/api/galaxies/' . $galaxy->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('galaxies', [
            'id' => $galaxy->id,
        ]);
    }
}
