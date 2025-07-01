<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the testPushNotification endpoint returns a server error when the FirebaseService fails.
     */
    public function test_test_push_notification_endpoint_returns_server_error()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/test-push-notification', [
            'user_id' => $user->user_id,
            'token'   => 'dummy-token',
            'title'   => 'Prueba Título',
            'body'    => 'Contenido de prueba',
            'data'    => ['foo' => 'bar'],
        ]);

        $response->assertStatus(500)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'error',
                 ])
                 ->assertJson([
                     'success' => false,
                     'message' => 'Ocurrió un error al enviar la notificación.',
                 ]);
    }

    /**
     * Test that validation errors are returned when input is inválido.
     */
    public function test_test_push_notification_validation_errors()
    {
        $response = $this->postJson('/api/test-push-notification', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id', 'token', 'title', 'body']);
    }
}
