<?php

namespace App\Services;

use App\Models\User;

class UserStateService
{
    public function __construct(
        protected HeartService $heartService,
        protected StreakService $streakService,
        protected XpService $xpService,
    ) {}

    public function build(User $user): array
    {
        $this->streakService->sync($user);
        $user->refresh();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone_number' => $user->phone_number,
            'email' => $user->email,
            'profile_img_url' => $user->profile_img_url,
            'role' => $user->role,
            'gender' => $user->gender,
            'birth_date' => $user->birth_date?->toDateString(),
            'major' => $user->major ? ['id' => $user->major->id, 'name' => $user->major->name] : null,
            'location' => [
                'country' => $user->country ? ['id' => $user->country->id, 'name' => $user->country->name] : null,
                'province' => $user->province ? ['id' => $user->province->id, 'name' => $user->province->name] : null,
                'city' => $user->city ? ['id' => $user->city->id, 'name' => $user->city->name] : null,
            ],
            'xp' => (int) $user->xp,
            'today_xp' => $this->xpService->todayXp($user),
            'hearts' => $this->heartService->state($user),
            'gems' => (int) $user->gems,
            'daily_goal' => (int) $user->daily_goal,
            'streak' => [
                'current' => (int) $user->current_streak,
                'longest' => (int) $user->longest_streak,
                'freezes_available' => (int) $user->streak_freezes_available,
            ],
            'progress' => $user->progress ?? [],
        ];
    }
}
