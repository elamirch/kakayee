<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\ApiTestCase;

class AuthTest extends ApiTestCase
{
    public function test_send_otp_creates_a_user_and_returns_debug_code(): void
    {
        $response = $this->postJson('/api/auth/send-otp', [
            'phone_number' => '09120000001',
        ]);

        $response->assertOk()
            ->assertJsonFragment(['debug_otp' => 11111]);

        $this->assertDatabaseHas('users', ['phone_number' => '09120000001']);
    }

    public function test_authenticate_returns_token_and_user_state(): void
    {
        $this->postJson('/api/auth/send-otp', ['phone_number' => '09120000002'])->assertOk();

        $response = $this->postJson('/api/auth/authenticate', [
            'phone_number' => '09120000002',
            'otp_code' => '11111',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user' => ['id', 'phone_number', 'xp', 'hearts', 'gems', 'streak'],
            ]);

        $this->assertArrayHasKey('access_token', $response->json());
    }

    public function test_invalid_otp_is_rejected(): void
    {
        $this->postJson('/api/auth/send-otp', ['phone_number' => '09120000003'])->assertOk();

        $this->postJson('/api/auth/authenticate', [
            'phone_number' => '09120000003',
            'otp_code' => '99999',
        ])->assertStatus(401);
    }

    public function test_profile_endpoint_requires_auth_and_returns_state(): void
    {
        $user = User::factory()->create();

        $this->getJson('/api/auth/profile')
            ->assertStatus(401);

        $response = $this->getJson('/api/auth/profile', $this->authHeaders($user));

        $response->assertOk()
            ->assertJsonStructure([
                'id', 'phone_number', 'xp', 'hearts', 'gems', 'daily_goal', 'streak',
            ]);
    }

    public function test_logout_all_invalidates_existing_tokens(): void
    {
        $user = User::factory()->create();
        $headers = $this->authHeaders($user);

        $this->postJson('/api/auth/logout-all', [], $headers)->assertOk();

        $user->refresh();
        $this->assertNotNull($user->last_logout);

        $this->getJson('/api/auth/profile', $headers)->assertStatus(401);
    }
}
