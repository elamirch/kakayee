<?php

namespace App\Http\Controllers;

use App\Services\HeartService;
use App\Services\StreakService;
use Illuminate\Http\Request;

class GamificationController extends Controller
{
    public function __construct(
        protected HeartService $heartService,
        protected StreakService $streakService,
    ) {}

    public function hearts(Request $request)
    {
        return response()->json(['hearts' => $this->heartService->state($request->user())]);
    }

    public function refillHearts(Request $request)
    {
        try {
            $state = $this->heartService->refillWithGems($request->user());
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Hearts refilled',
            'hearts' => $state,
            'gems' => $request->user()->gems,
        ]);
    }

    public function buyStreakFreeze(Request $request)
    {
        try {
            $freezes = $this->streakService->buyFreeze($request->user());
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Streak freeze purchased',
            'streak_freezes_available' => $freezes,
            'gems' => $request->user()->gems,
        ]);
    }
}
