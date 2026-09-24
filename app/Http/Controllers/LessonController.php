<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        $lessons = Lesson::query()
            ->where('course_id', $validated['course_id'])
            ->withCount('cards')
            ->orderBy('id')
            ->get();

        $user = $request->user();
        $progress = $user->progress ?? [];

        return response()->json([
            'lessons' => $lessons->map(function ($lesson) use ($progress) {
                $state = $progress[$lesson->id] ?? [];

                return [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'cards_count' => (int) $lesson->cards_count,
                    'completed' => (bool) ($state['completed'] ?? false),
                    'checkpoint' => (int) ($state['checkpoint'] ?? 0),
                ];
            })->values(),
        ]);
    }

    public function show(Request $request, Lesson $lesson)
    {
        $user = $request->user();
        $progress = $user->progress ?? [];
        $state = $progress[$lesson->id] ?? [];

        return response()->json([
            'lesson' => [
                'id' => $lesson->id,
                'name' => $lesson->name,
                'notes' => $lesson->notes,
                'course' => $lesson->course ? ['id' => $lesson->course->id, 'name' => $lesson->course->name] : null,
                'completed' => (bool) ($state['completed'] ?? false),
                'checkpoint' => (int) ($state['checkpoint'] ?? 0),
            ],
        ]);
    }
}
