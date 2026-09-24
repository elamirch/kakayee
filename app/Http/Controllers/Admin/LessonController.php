<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'notes' => 'sometimes|nullable',
            'course_id' => 'nullable|integer|exists:courses,id',
        ]);

        $lesson = Lesson::create([
            'name' => $this->translatable($validated['name']),
            'notes' => isset($validated['notes']) && $validated['notes'] !== null ? $this->translatable($validated['notes']) : null,
            'course_id' => $validated['course_id'] ?? null,
        ]);

        return response()->json(['lesson' => $lesson], 201);
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required',
            'notes' => 'sometimes|nullable',
            'course_id' => 'nullable|integer|exists:courses,id',
        ]);

        if (isset($validated['name'])) {
            $lesson->name = $this->translatable($validated['name']);
        }
        if (array_key_exists('notes', $validated)) {
            $lesson->notes = $validated['notes'] !== null ? $this->translatable($validated['notes']) : null;
        }
        if (array_key_exists('course_id', $validated)) {
            $lesson->course_id = $validated['course_id'];
        }
        $lesson->save();

        return response()->json(['lesson' => $lesson]);
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response()->json(null, 204);
    }

    protected function translatable($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        return [config('gamification.default_locale') => $value];
    }
}
