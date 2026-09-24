<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'major_id' => 'nullable|integer|exists:majors,id',
        ]);

        $course = Course::create([
            'name' => $this->translatable($validated['name']),
            'major_id' => $validated['major_id'] ?? null,
        ]);

        return response()->json(['course' => $course], 201);
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required',
            'major_id' => 'nullable|integer|exists:majors,id',
        ]);

        if (isset($validated['name'])) {
            $course->name = $this->translatable($validated['name']);
        }
        if (array_key_exists('major_id', $validated)) {
            $course->major_id = $validated['major_id'];
        }
        $course->save();

        return response()->json(['course' => $course]);
    }

    public function destroy(Course $course)
    {
        $course->delete();

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
