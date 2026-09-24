<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Carbon;

class HeartService
{
    public function __construct(protected GemService $gemService) {}

    public function maxHearts(): int
    {
        return config('gamification.hearts.max');
    }

    public function refillMinutes(): int
    {
        return config('gamification.hearts.refill_minutes');
    }

    /**
     * Current heart state, computed lazily from the last refill timestamp.
     */
    public function state(User $user): array
    {
        $max = $this->maxHearts();
        $current = (int) $user->heart;
        $lastRefill = $user->heart_refilled_at;

        if ($current >= $max) {
            return [
                'hearts' => $max,
                'max' => $max,
                'next_refill_at' => null,
            ];
        }

        $nextRefillAt = $lastRefill ? $lastRefill->copy()->addMinutes($this->refillMinutes()) : null;

        while ($current < $max && $nextRefillAt && $nextRefillAt->lte(Carbon::now())) {
            $current++;
            $nextRefillAt->addMinutes($this->refillMinutes());
        }

        if ($current >= $max) {
            $this->persist($user, $max, null);

            return [
                'hearts' => $max,
                'max' => $max,
                'next_refill_at' => null,
            ];
        }

        $this->persist($user, $current, $lastRefill);
        $user->refresh();

        return [
            'hearts' => $current,
            'max' => $max,
            'next_refill_at' => $nextRefillAt,
        ];
    }

    public function loseOne(User $user): array
    {
        $state = $this->state($user);
        $current = $state['hearts'];
        $current = max(0, $current - 1);

        $this->persist($user, $current, $user->heart_refilled_at);

        return $this->state($user);
    }

    public function restore(User $user, int $amount = 1): array
    {
        $state = $this->state($user);
        $current = min($this->maxHearts(), $state['hearts'] + $amount);

        $this->persist($user, $current, $user->heart_refilled_at);

        return $this->state($user);
    }

    public function refillWithGems(User $user): array
    {
        $cost = config('gamification.hearts.refill_gem_cost');
        $this->gemService->deduct($user, $cost, 'heart_refill');

        $this->persist($user, $this->maxHearts(), null);

        return $this->state($user);
    }

    private function persist(User $user, int $hearts, ?Carbon $lastRefill): void
    {
        $user->heart = $hearts;
        $user->heart_refilled_at = $lastRefill;
        $user->save();
    }
}
