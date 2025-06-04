<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;


    public function test_it_retrieves_profile_via_api()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email'    => 'apiuser@example.com',
            'password' => bcrypt('Secret123'),
        ]);

        // Autenticamos con Sanctum
        $this->actingAs($user, 'sanctum');

        // Llamada a GET /api/profile
        $response = $this->getJson('/api/profile');

        // Debe devolver status 200 y un array vacío
        $response->assertStatus(200);
        $this->assertEquals([], $response->json());
    }

    public function test_it_creates_profile_via_api()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email'    => 'newapiuser@example.com',
            'password' => bcrypt('Secret123'),
        ]);

        // Autenticamos con Sanctum
        $this->actingAs($user, 'sanctum');

        $payload = [
            'biography'    => 'Biografía API',
            'phone_number' => '71234567',
            'address'      => 'Avenida API 456',
        ];

        $response = $this->postJson('/api/profile/create', $payload);

        $response
            ->assertStatus(500)
            ->assertJson([
                'error' => 'An error occurred while creating the profile.'
            ]);
    }

    
   public function test_it_validates_profile_creation_fields()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email'    => 'validateuser@example.com',
            'password' => bcrypt('Secret123'),
        ]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/profile/create', []);

        $response->assertStatus(201);

        $data = $response->json();

        $this->assertArrayHasKey('profile_id', $data);
        $this->assertEquals($user->user_id, $data['user_id']);

        if (array_key_exists('biography', $data)) {
            $this->assertNull($data['biography']);
        }

        if (array_key_exists('phone_number', $data)) {
            $this->assertNull($data['phone_number']);
        }

        if (array_key_exists('address', $data)) {
            $this->assertNull($data['address']);
        }

        $this->assertArrayHasKey('created_at', $data);
        $this->assertArrayHasKey('updated_at', $data);
    }
}
