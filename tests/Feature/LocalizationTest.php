<?php

namespace Tests\Feature;

use App\Models\Major;
use App\Models\User;
use Tests\ApiTestCase;

class LocalizationTest extends ApiTestCase
{
    public function test_major_name_is_returned_in_requested_locale(): void
    {
        Major::create([
            'name' => ['en' => 'Medical Sciences', 'fa' => 'علوم پزشکی'],
        ]);

        $user = User::factory()->create();

        $en = $this->getJson('/api/majors?lang=en', $this->authHeaders($user));
        $en->assertOk()->assertJsonFragment(['name' => 'Medical Sciences']);

        $fa = $this->getJson('/api/majors?lang=fa', $this->authHeaders($user));
        $fa->assertOk()->assertJsonFragment(['name' => 'علوم پزشکی']);
    }

    public function test_accept_language_header_is_respected(): void
    {
        Major::create([
            'name' => ['en' => 'Medical Sciences', 'fa' => 'علوم پزشکی'],
        ]);

        $user = User::factory()->create();
        $headers = array_merge($this->authHeaders($user), ['Accept-Language' => 'fa']);

        $this->getJson('/api/majors', $headers)
            ->assertOk()
            ->assertJsonFragment(['name' => 'علوم پزشکی']);
    }
}
