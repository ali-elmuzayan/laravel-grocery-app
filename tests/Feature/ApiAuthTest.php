<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_tokens(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Ali',
            'email' => 'ali@example.com',
            'password' => 'Password123!',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure([
                'access_token',
                'refresh_token',
                'token_type',
            ]);
    }
}
