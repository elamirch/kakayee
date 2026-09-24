<?php

namespace App\Services;

use App\Models\Quest;
use App\Models\User;
use App\Models\UserQuest;
use Illuminate\Support\Carbon;

class QuestService
{
    public function __construct(protected XpService $xpService, protected GemService $gemService) {}

    public function timezone(): string
    {
        return config('gamification.streak_timezone', 'Asia/Tehran');
    }

    public function currentPeriod(string $type): array
    {
        $now = Carbon::now($this->timezone());

        if ($type === 'weekly') {
            return [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek(),
            ];
        }

        return [
            'start' => $now->copy()->startOfDay(),
            'end' => $now->copy()->endOfDay(),
        ];
    }

    /**
     * Make sure the user has a user_quests row for every active quest in the
     * current period.
     */
    public function ensureActive(User $user): void
    {
        $quests = Quest::all();

        foreach ($quests as $quest) {
            [$start, $end] = array_values($this->currentPeriod($quest->type));

            UserQuest::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'quest_id' => $quest->id,
                    'period_start' => $start->toDateString(),
                ],
                [
                    'period_end' => $end->toDateString(),
                    'progress' => 0,
                ]
            );
        }
    }

    /**
     * Advance progress for quests matching a key (e.g. 'earn_xp', 'complete_lesson').
     */
    public function registerProgress(User $user, string $key, int $amount = 1): void
    {
        $this->ensureActive($user);

        $quests = Quest::where('key', $key)->get();
        if ($quests->isEmpty()) {
            return;
        }

        foreach ($quests as $quest) {
            [$start, $end] = array_values($this->currentPeriod($quest->type));

            $userQuest = UserQuest::firstOrNew([
                'user_id' => $user->id,
                'quest_id' => $quest->id,
                'period_start' => $start->toDateString(),
            ], [
                'period_end' => $end->toDateString(),
                'progress' => 0,
            ]);

            if ($userQuest->completed_at) {
                continue;
            }

            $userQuest->progress = min($quest->target, (int) $userQuest->progress + $amount);
            if ($userQuest->progress >= $quest->target) {
                $userQuest->completed_at = Carbon::now();
            }
            $userQuest->save();
        }
    }

    public function claim(User $user, UserQuest $userQuest): array
    {
        if ($userQuest->user_id !== $user->id) {
            abort(403);
        }
        if (! $userQuest->completed_at) {
            throw new \RuntimeException('Quest not completed yet');
        }
        if ($userQuest->claimed_at) {
            throw new \RuntimeException('Quest already claimed');
        }

        $quest = $userQuest->quest;

        $userQuest->claimed_at = Carbon::now();
        $userQuest->save();

        if ($quest->xp_reward > 0) {
            $this->xpService->add($user, $quest->xp_reward, 'quest', ['quest_key' => $quest->key]);
        }
        if ($quest->gem_reward > 0) {
            $this->gemService->add($user, $quest->gem_reward, 'quest_reward');
        }

        return $userQuest->refresh()->toArray();
    }
}
