<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use App\Models\Province;
use App\Models\User;
use Tests\ApiTestCase;

class LeaderboardTest extends ApiTestCase
{
    private function makeLocation(string $country, string $province, string $city, string $iso2 = 'XX'): array
    {
        $c = Country::create(['name' => ['en' => $country], 'iso2' => $iso2]);
        $p = Province::create(['name' => ['en' => $province], 'country_id' => $c->id]);
        $city = City::create(['name' => ['en' => $city], 'province_id' => $p->id]);

        return [$c, $p, $city];
    }

    public function test_universal_leaderboard_is_ordered_by_xp(): void
    {
        [$c, $p, $city] = $this->makeLocation('Iran', 'Tehran', 'Tehran City');
        $low = User::factory()->create(['xp' => 100, 'city_id' => $city->id]);
        $high = User::factory()->create(['xp' => 900, 'city_id' => $city->id]);

        $response = $this->getJson('/api/leaderboard?scope=universal', $this->authHeaders($low));

        $response->assertOk()
            ->assertJsonPath('scope', 'universal');

        $scores = collect($response->json('data'))->pluck('user.score');
        $this->assertEquals($scores->sortDesc()->values()->all(), $scores->all());
        $this->assertTrue($scores->contains(900));
        $this->assertTrue($scores->contains(100));
    }

    public function test_city_leaderboard_only_includes_same_city(): void
    {
        [$c, $p, $tehran] = $this->makeLocation('Iran', 'Tehran', 'Tehran City', 'IR');
        [$c2, $p2, $isfahan] = $this->makeLocation('Iran', 'Isfahan', 'Isfahan City', 'IS');

        $me = User::factory()->create(['xp' => 300, 'city_id' => $tehran->id]);
        User::factory()->create(['xp' => 500, 'city_id' => $isfahan->id]);
        User::factory()->create(['xp' => 200, 'city_id' => $tehran->id]);

        $response = $this->getJson('/api/leaderboard?scope=city', $this->authHeaders($me));

        $response->assertOk()->assertJsonPath('scope', 'city');

        $cities = collect($response->json('data'))->pluck('user.city.id')->unique();
        $this->assertEquals([$tehran->id], $cities->all());
    }

    public function test_current_user_rank_is_reported(): void
    {
        [$c, $p, $city] = $this->makeLocation('Iran', 'Tehran', 'Tehran City');
        $me = User::factory()->create(['xp' => 150, 'city_id' => $city->id]);
        User::factory()->create(['xp' => 1000, 'city_id' => $city->id]);
        User::factory()->create(['xp' => 300, 'city_id' => $city->id]);

        $response = $this->getJson('/api/leaderboard?scope=city', $this->authHeaders($me));

        $response->assertOk()->assertJsonPath('current_user.rank', 3);
    }

    public function test_weekly_leaderboard_aggregates_xp_transactions(): void
    {
        [$c, $p, $city] = $this->makeLocation('Iran', 'Tehran', 'Tehran City');
        $me = User::factory()->create(['xp' => 0, 'city_id' => $city->id]);

        \App\Models\XpTransaction::create([
            'user_id' => $me->id,
            'amount' => 40,
            'reason' => 'answer_correct',
            'created_at' => now(),
        ]);

        $other = User::factory()->create(['xp' => 0, 'city_id' => $city->id]);
        \App\Models\XpTransaction::create([
            'user_id' => $other->id,
            'amount' => 90,
            'reason' => 'answer_correct',
            'created_at' => now(),
        ]);

        $response = $this->getJson('/api/leaderboard?scope=city&period=weekly', $this->authHeaders($me));

        $response->assertOk()->assertJsonPath('period', 'weekly');

        $scores = collect($response->json('data'))->pluck('user.score');
        $this->assertEquals([90, 40], $scores->values()->all());

        $this->assertEquals(2, $response->json('current_user.rank'));
        $this->assertEquals(40, $response->json('current_user.score'));
    }
}
