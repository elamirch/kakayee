<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()
            ->withCount('lessons')
            ->when($request->has('major_id'), fn ($q) => $q->where('major_id', $request->integer('major_id')));

        $courses = $query->get();

        return response()->json([
            'courses' => $courses->map(fn ($course) => [
                'id' => $course->id,
                'name' => $course->name,
                'major_id' => $course->major_id,
                'lessons_count' => (int) $course->lessons_count,
            ]),
        ]);
    }

    public function show(Course $course)
    {
        $course->load('lessons');

        return response()->json([
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
                'major' => $course->major ? ['id' => $course->major->id, 'name' => $course->major->name] : null,
                'lessons' => $course->lessons->map(fn ($lesson) => [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'cards_count' => $lesson->cards()->count(),
                ])->values(),
            ],
        ]);
    }

    /**
     * Select the user's active course.
     */
    public function select(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        $user = $request->user();
        $user->major_id = Course::findOrFail($validated['course_id'])->major_id;
        $user->save();

        return response()->json(['message' => 'Course selected']);
    }
}
