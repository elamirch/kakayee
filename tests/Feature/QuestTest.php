<?php

namespace Tests\Feature;

use App\Models\Quest;
use App\Models\User;
use App\Services\XpService;
use Tests\ApiTestCase;

class QuestTest extends ApiTestCase
{
    public function test_earn_xp_quest_progresses_and_can_be_claimed(): void
    {
        $quest = Quest::create([
            'key' => 'earn_xp',
            'type' => 'daily',
            'title' => ['en' => 'Earn 50 XP', 'fa' => 'کسب ۵۰ ایکسپی'],
            'target' => 50,
            'xp_reward' => 10,
            'gem_reward' => 5,
        ]);

        $user = User::factory()->create(['xp' => 0, 'gems' => 0]);
        $headers = $this->authHeaders($user);

        // Earn enough XP to finish the quest.
        app(XpService::class)->add($user, 60, 'answer_correct');

        $response = $this->getJson('/api/quests', $headers);

        $response->assertOk()
            ->assertJsonPath('quests.0.key', 'earn_xp')
            ->assertJsonPath('quests.0.progress', 50)
            ->assertJsonPath('quests.0.completed', true)
            ->assertJsonPath('quests.0.claimed', false);

        $questId = $response->json('quests.0.id');

        $claim = $this->postJson("/api/quests/{$questId}/claim", [], $headers);
        $claim->assertOk();

        $this->assertEquals(70, $user->refresh()->xp);
        $this->assertEquals(5, $user->refresh()->gems);
    }

    public function test_claiming_incomplete_quest_fails(): void
    {
        Quest::create([
            'key' => 'earn_xp',
            'type' => 'daily',
            'title' => ['en' => 'Earn 50 XP'],
            'target' => 50,
        ]);

        $user = User::factory()->create(['xp' => 0]);
        $headers = $this->authHeaders($user);

        $this->getJson('/api/quests', $headers)->assertOk();

        $userQuest = \App\Models\UserQuest::first();

        $this->postJson("/api/quests/{$userQuest->id}/claim", [], $headers)
            ->assertStatus(422);
    }
}
