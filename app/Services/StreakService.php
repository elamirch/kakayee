<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Carbon;

class StreakService
{
    public function __construct(protected GemService $gemService) {}

    protected function timezone(): string
    {
        return config('gamification.streak_timezone', 'Asia/Tehran');
    }

    public function today(): Carbon
    {
        return Carbon::now($this->timezone())->startOfDay();
    }

    /**
     * Record activity for a user and recompute the streak. Must be called
     * while $user->last_active_at still holds the previous activity time.
     */
    public function touch(User $user): void
    {
        $today = $this->today();
        $last = $user->last_active_at
            ? $user->last_active_at->copy()->setTimezone($this->timezone())->startOfDay()
            : null;

        if ($last === null) {
            $streak = 1;
        } elseif ($last->eq($today)) {
            $streak = (int) $user->current_streak;
        } elseif ($last->eq($today->copy()->subDay())) {
            $streak = (int) $user->current_streak + 1;
        } else {
            $missedDays = (int) $last->diffInDays($today);
            if ($missedDays === 1 && $user->streak_freezes_available > 0) {
                $user->decrement('streak_freezes_available');
                $streak = (int) $user->current_streak + 1;
            } else {
                $streak = 1;
            }
        }

        $user->current_streak = $streak;
        $user->longest_streak = max((int) $user->longest_streak, $streak);
        $user->last_active_at = Carbon::now();
        $user->save();
    }

    /**
     * Sync streak based purely on the user's stored state (called lazily on read).
     */
    public function sync(User $user): void
    {
        $today = $this->today();
        $last = $user->last_active_at
            ? $user->last_active_at->copy()->setTimezone($this->timezone())->startOfDay()
            : null;

        if ($last !== null && $last->lt($today->copy()->subDay())) {
            $missedDays = (int) $last->diffInDays($today);
            if ($missedDays === 1 && $user->streak_freezes_available > 0) {
                $user->decrement('streak_freezes_available');
            } else {
                $user->current_streak = 0;
            }
            $user->save();
        }
    }

    public function buyFreeze(User $user): int
    {
        $cost = config('gamification.gems.streak_freeze_cost');
        $this->gemService->deduct($user, $cost, 'streak_freeze');
        $user->increment('streak_freezes_available');

        return $user->streak_freezes_available;
    }
}
