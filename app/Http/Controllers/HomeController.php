<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\UserStateService;
use App\Services\XpService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        protected UserStateService $userState,
        protected XpService $xpService,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        $courses = Course::query()
            ->with(['lessons' => fn ($q) => $q->withCount('cards')])
            ->withCount('lessons')
            ->orderBy('id')
            ->get();

        $progress = $user->progress ?? [];
        $todayXp = $this->xpService->todayXp($user);

        $majorName = null;
        if ($user->major) {
            $majorName = $user->major->name;
        }

        return response()->json([
            'user' => $this->userState->build($user),
            'daily_goal_progress' => [
                'target' => (int) $user->daily_goal,
                'current' => $todayXp,
                'reached' => $todayXp >= (int) $user->daily_goal,
            ],
            'weekly_xp' => $this->xpService->weekXp($user),
            'major' => $majorName ? ['id' => $user->major_id, 'name' => $majorName] : null,
            'courses' => $courses->map(function ($course) use ($progress) {
                $completedLessons = 0;
                foreach ($course->lessons as $lesson) {
                    if (($progress[$lesson->id]['completed'] ?? false) === true) {
                        $completedLessons++;
                    }
                }

                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'lessons_count' => (int) $course->lessons_count,
                    'completed_lessons' => $completedLessons,
                    'lessons' => $course->lessons->map(fn ($lesson) => [
                        'id' => $lesson->id,
                        'name' => $lesson->name,
                        'cards_count' => (int) $lesson->cards_count,
                        'completed' => (bool) ($progress[$lesson->id]['completed'] ?? false),
                    ])->values(),
                ];
            })->values(),
        ]);
    }
}
