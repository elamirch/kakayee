<?php

namespace App\Http\Controllers;

use App\Models\UserQuest;
use App\Services\QuestService;
use Illuminate\Http\Request;

class QuestController extends Controller
{
    public function __construct(protected QuestService $questService) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $this->questService->ensureActive($user);

        $userQuests = UserQuest::query()
            ->with('quest')
            ->where('user_id', $user->id)
            ->orderBy('period_end', 'desc')
            ->get();

        return response()->json([
            'quests' => $userQuests->map(fn ($userQuest) => [
                'id' => $userQuest->id,
                'key' => $userQuest->quest->key,
                'type' => $userQuest->quest->type,
                'title' => $userQuest->quest->title,
                'target' => $userQuest->quest->target,
                'progress' => (int) $userQuest->progress,
                'xp_reward' => $userQuest->quest->xp_reward,
                'gem_reward' => $userQuest->quest->gem_reward,
                'completed' => (bool) $userQuest->completed_at,
                'claimed' => (bool) $userQuest->claimed_at,
                'period_start' => $userQuest->period_start,
                'period_end' => $userQuest->period_end,
            ])->values(),
        ]);
    }

    public function claim(Request $request, UserQuest $userQuest)
    {
        try {
            $this->questService->claim($request->user(), $userQuest);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Quest claimed']);
    }
}
