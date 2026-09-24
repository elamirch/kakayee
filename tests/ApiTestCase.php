<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected function authHeaders(User $user, array $extra = []): array
    {
        $token = JWTAuth::claims(['last_logout' => 0])->fromUser($user);

        return array_merge([
            'Authorization' => 'Bearer '.$token,
            'Accept' => 'application/json',
        ], $extra);
    }

    protected function authenticateViaOtp(string $phone = '09120000001', string $otp = '11111'): array
    {
        $this->postJson('/api/auth/send-otp', ['phone_number' => $phone])->assertOk();

        $response = $this->postJson('/api/auth/authenticate', [
            'phone_number' => $phone,
            'otp_code' => $otp,
        ])->assertOk();

        return $response->json();
    }
}
