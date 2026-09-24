<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'answer' => 'required',
            'explanation' => 'sometimes|nullable',
            'date' => 'sometimes|nullable|string',
            'order' => 'sometimes|nullable|integer',
            'lesson_id' => 'nullable|integer|exists:lessons,id',
            'source_id' => 'nullable|integer|exists:sources,id',
        ]);

        $card = Card::create([
            'title' => $this->translatable($validated['title']),
            'content' => $this->translatable($validated['content']),
            'answer' => $this->translatable($validated['answer']),
            'explanation' => isset($validated['explanation']) && $validated['explanation'] !== null ? $this->translatable($validated['explanation']) : null,
            'date' => $validated['date'] ?? null,
            'order' => $validated['order'] ?? 0,
            'lesson_id' => $validated['lesson_id'] ?? null,
            'source_id' => $validated['source_id'] ?? null,
        ]);

        return response()->json(['card' => $card], 201);
    }

    public function update(Request $request, Card $card)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required',
            'content' => 'sometimes|required',
            'answer' => 'sometimes|required',
            'explanation' => 'sometimes|nullable',
            'date' => 'sometimes|nullable|string',
            'order' => 'sometimes|nullable|integer',
            'lesson_id' => 'nullable|integer|exists:lessons,id',
            'source_id' => 'nullable|integer|exists:sources,id',
        ]);

        foreach (['title', 'content', 'answer', 'explanation'] as $field) {
            if (array_key_exists($field, $validated)) {
                $card->{$field} = $validated[$field] !== null ? $this->translatable($validated[$field]) : null;
            }
        }
        foreach (['date', 'order', 'lesson_id', 'source_id'] as $field) {
            if (array_key_exists($field, $validated)) {
                $card->{$field} = $validated[$field];
            }
        }
        $card->save();

        return response()->json(['card' => $card]);
    }

    public function destroy(Card $card)
    {
        $card->delete();

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
