<?php

namespace App\Services;

use App\Models\User;

class GemService
{
    public function add(User $user, int $amount, string $reason): int
    {
        $user->increment('gems', $amount);

        return $user->gems;
    }

    public function deduct(User $user, int $amount, string $reason): int
    {
        $current = (int) $user->gems;
        if ($current < $amount) {
            throw new \RuntimeException('Not enough gems');
        }
        $user->decrement('gems', $amount);

        return $user->gems;
    }
}
