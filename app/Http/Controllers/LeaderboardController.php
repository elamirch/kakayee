<?php

namespace App\Http\Controllers;

use App\Services\LeaderboardService;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function __construct(protected LeaderboardService $leaderboard) {}

    public function index(Request $request)
    {
        $validated = $request->validate([
            'scope' => 'nullable|string|in:universal,country,province,city',
            'period' => 'nullable|string|in:all_time,weekly,daily',
        ]);

        $result = $this->leaderboard->get(
            $request->user(),
            $validated['scope'] ?? 'universal',
            $validated['period'] ?? 'all_time',
            max(1, $request->integer('page', 1))
        );

        return response()->json($result);
    }
}
