<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\XpTransaction;
use Illuminate\Support\Carbon;

class AchievementService
{
    public function __construct(protected XpService $xpService, protected GemService $gemService) {}

    public function check(User $user): void
    {
        $achievements = Achievement::all();
        if ($achievements->isEmpty()) {
            return;
        }

        $stats = $this->stats($user);
        $unlockedKeys = UserAchievement::query()
            ->where('user_id', $user->id)
            ->pluck('achievement_id')
            ->all();

        foreach ($achievements as $achievement) {
            if (in_array($achievement->id, $unlockedKeys, true)) {
                continue;
            }
            if (! $this->satisfies($achievement->condition, $stats)) {
                continue;
            }

            UserAchievement::create([
                'user_id' => $user->id,
                'achievement_id' => $achievement->id,
                'unlocked_at' => Carbon::now(),
            ]);

            if ($achievement->xp_reward > 0) {
                $this->xpService->add($user, $achievement->xp_reward, 'achievement', ['achievement_key' => $achievement->key]);
            }
            if ($achievement->gem_reward > 0) {
                $this->gemService->add($user, $achievement->gem_reward, 'achievement_reward');
            }
        }
    }

    public function stats(User $user): array
    {
        $completedLessons = 0;
        foreach ((array) ($user->progress ?? []) as $lesson) {
            if (isset($lesson['completed']) && $lesson['completed']) {
                $completedLessons++;
            }
        }

        return [
            'total_xp' => (int) $user->xp,
            'current_streak' => (int) $user->current_streak,
            'gems' => (int) $user->gems,
            'lessons_completed' => $completedLessons,
            'correct_answers' => (int) XpTransaction::query()
                ->where('user_id', $user->id)
                ->where('reason', 'answer_correct')
                ->count(),
            'practice_completed' => (int) XpTransaction::query()
                ->where('user_id', $user->id)
                ->where('reason', 'practice_complete')
                ->count(),
            'perfect_lessons' => (int) XpTransaction::query()
                ->where('user_id', $user->id)
                ->where('reason', 'perfect_lesson')
                ->count(),
        ];
    }

    protected function satisfies(array $condition, array $stats): bool
    {
        $type = $condition['type'] ?? null;
        $value = (int) ($condition['value'] ?? 0);

        return match ($type) {
            'total_xp' => $stats['total_xp'] >= $value,
            'current_streak' => $stats['current_streak'] >= $value,
            'gems' => $stats['gems'] >= $value,
            'lessons_completed' => $stats['lessons_completed'] >= $value,
            'correct_answers' => $stats['correct_answers'] >= $value,
            'practice_completed' => $stats['practice_completed'] >= $value,
            'perfect_lessons' => $stats['perfect_lessons'] >= $value,
            default => false,
        };
    }
}
