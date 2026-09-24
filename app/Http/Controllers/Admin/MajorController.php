<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $major = Major::create(['name' => $this->translatable($validated['name'])]);

        return response()->json(['major' => $major], 201);
    }

    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required',
        ]);

        if (isset($validated['name'])) {
            $major->name = $this->translatable($validated['name']);
        }
        $major->save();

        return response()->json(['major' => $major]);
    }

    public function destroy(Major $major)
    {
        $major->delete();

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
