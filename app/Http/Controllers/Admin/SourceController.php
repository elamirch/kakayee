<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'source' => 'required',
        ]);

        $source = Source::create(['source' => $this->translatable($validated['source'])]);

        return response()->json(['source' => $source], 201);
    }

    public function update(Request $request, Source $source)
    {
        $validated = $request->validate([
            'source' => 'sometimes|required',
        ]);

        if (isset($validated['source'])) {
            $source->source = $this->translatable($validated['source']);
        }
        $source->save();

        return response()->json(['source' => $source]);
    }

    public function destroy(Source $source)
    {
        $source->delete();

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
