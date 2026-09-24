<?php

namespace App\Http\Controllers;

use App\Models\Major;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::withCount('courses')->get();

        return response()->json([
            'majors' => $majors->map(fn ($major) => [
                'id' => $major->id,
                'name' => $major->name,
                'courses_count' => (int) $major->courses_count,
            ]),
        ]);
    }

    public function show(Major $major)
    {
        return response()->json([
            'major' => [
                'id' => $major->id,
                'name' => $major->name,
            ],
        ]);
    }
}
