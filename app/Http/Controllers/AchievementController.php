<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\UserAchievement;
use App\Services\AchievementService;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function __construct(protected AchievementService $achievementService) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $unlocked = UserAchievement::query()
            ->where('user_id', $user->id)
            ->pluck('achievement_id')
            ->all();

        $achievements = Achievement::all()->map(function ($achievement) use ($unlocked) {
            return [
                'id' => $achievement->id,
                'key' => $achievement->key,
                'title' => $achievement->title,
                'description' => $achievement->description,
                'condition' => $achievement->condition,
                'xp_reward' => $achievement->xp_reward,
                'gem_reward' => $achievement->gem_reward,
                'unlocked' => in_array($achievement->id, $unlocked, true),
            ];
        });

        return response()->json([
            'achievements' => $achievements->values(),
            'stats' => $this->achievementService->stats($user),
        ]);
    }
}
