<?php

namespace App\Services;

use App\Events\XpAwarded;
use App\Models\User;
use App\Models\XpTransaction;
use Illuminate\Support\Carbon;

class XpService
{
    public function __construct(protected StreakService $streakService) {}

    /**
     * Award XP to a user, record the transaction and refresh derived state.
     */
    public function add(User $user, int $amount, string $reason, array $metadata = []): int
    {
        if ($amount === 0) {
            return 0;
        }

        XpTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'reason' => $reason,
            'metadata' => $metadata ?: null,
            'created_at' => Carbon::now(),
        ]);

        $user->increment('xp', $amount);

        $this->streakService->touch($user);

        event(new XpAwarded($user, $amount, $reason, $metadata));

        return $amount;
    }

    /**
     * How many XP the user earned today.
     */
    public function todayXp(User $user): int
    {
        return (int) XpTransaction::query()
            ->where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->setTimezone(config('gamification.streak_timezone'))->startOfDay())
            ->sum('amount');
    }

    /**
     * How many XP the user earned during the current week (streak timezone).
     */
    public function weekXp(User $user): int
    {
        return (int) XpTransaction::query()
            ->where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->setTimezone(config('gamification.streak_timezone'))->startOfWeek())
            ->sum('amount');
    }
}
