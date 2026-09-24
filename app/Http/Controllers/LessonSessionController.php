<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonSessionController extends Controller
{
    /**
     * Start a lesson session and get its cards (answers hidden).
     */
    public function start(Request $request, Lesson $lesson)
    {
        $user = $request->user();
        $progress = $user->progress ?? [];
        $state = $progress[$lesson->id] ?? [];

        $progress[$lesson->id] = [
            'completed' => (bool) ($state['completed'] ?? false),
            'checkpoint' => 0,
            'correct' => 0,
            'perfect' => true,
        ];
        $user->progress = $progress;
        $user->save();

        $cards = $lesson->cards()->orderBy('order')->orderBy('id')->get();

        return response()->json([
            'lesson' => [
                'id' => $lesson->id,
                'name' => $lesson->name,
            ],
            'cards' => $cards->map(fn ($card) => [
                'id' => $card->id,
                'title' => $card->title,
                'content' => $card->content,
                'date' => $card->date,
                'source' => $card->source ? $card->source->source : null,
            ])->values(),
            'total_cards' => $cards->count(),
        ]);
    }

    /**
     * Check a card answer, award XP / lose hearts, advance progress.
     */
    public function answer(Request $request, Card $card)
    {
        $validated = $request->validate([
            'answer' => 'required|string',
            'practice' => 'sometimes|boolean',
        ]);

        $user = $request->user();
        $practice = (bool) ($validated['practice'] ?? false);
        $lesson = $card->lesson;
        $progress = $user->progress ?? [];

        if (! $lesson || $lesson->id === null) {
            return response()->json(['error' => 'Card has no lesson'], 422);
        }

        $correctAnswer = trim(mb_strtolower((string) $card->answer));
        $submitted = trim(mb_strtolower($validated['answer']));
        $isCorrect = $correctAnswer === $submitted;

        $bonus = $user->current_streak >= 3 ? config('gamification.xp.streak_bonus', 2) : 0;

        if ($isCorrect) {
            $totalCards = $lesson->cards()->count();
            $xpService = app(\App\Services\XpService::class);

            if ($practice) {
                $practiceState = $progress[$lesson->id]['practice'] ?? ['checkpoint' => 0, 'correct' => 0];
                $practiceState['checkpoint'] = min($totalCards, (int) $practiceState['checkpoint'] + 1);
                $practiceState['correct'] += 1;
                $progress[$lesson->id]['practice'] = $practiceState;
                $user->progress = $progress;
                $user->save();

                $earnedXp = config('gamification.xp.practice_correct', 2) + $bonus;
                $xpService->add($user, $earnedXp, 'practice_correct', ['card_id' => $card->id, 'lesson_id' => $lesson->id]);

                return response()->json([
                    'correct' => true,
                    'earned_xp' => $earnedXp,
                    'practice_completed' => $practiceState['checkpoint'] >= $totalCards,
                    'hearts' => app(\App\Services\HeartService::class)->state($user),
                ]);
            }

            $state = $progress[$lesson->id] ?? ['completed' => false, 'checkpoint' => 0, 'correct' => 0, 'perfect' => true];
            $state['completed'] = (bool) ($state['completed'] ?? false);
            $state['checkpoint'] = (int) ($state['checkpoint'] ?? 0);
            $state['correct'] = (int) ($state['correct'] ?? 0);
            $state['perfect'] = (bool) ($state['perfect'] ?? true);

            $state['checkpoint'] = min($totalCards, $state['checkpoint'] + 1);
            $state['correct'] += 1;

            $earnedXp = config('gamification.xp.answer_correct', 10) + $bonus;
            $xpService->add($user, $earnedXp, 'answer_correct', ['card_id' => $card->id, 'lesson_id' => $lesson->id]);

            $lessonCompleted = false;
            if ($state['checkpoint'] >= $totalCards && ! $state['completed']) {
                $state['completed'] = true;
                $lessonCompleted = true;

                $xpService->add($user, config('gamification.xp.lesson_complete', 10), 'lesson_complete', ['lesson_id' => $lesson->id]);

                if ($state['perfect'] && $state['correct'] >= $totalCards) {
                    $xpService->add($user, config('gamification.xp.perfect_lesson_bonus', 5), 'perfect_lesson', ['lesson_id' => $lesson->id]);
                    app(\App\Services\GemService::class)->add($user, config('gamification.gems.perfect_lesson_reward', 10), 'perfect_lesson');
                }

                app(\App\Services\QuestService::class)->registerProgress($user, 'complete_lesson', 1);
            }

            $progress[$lesson->id] = $state;
            $user->progress = $progress;
            $user->save();

            return response()->json([
                'correct' => true,
                'earned_xp' => $earnedXp,
                'lesson_completed' => $lessonCompleted,
                'hearts' => app(\App\Services\HeartService::class)->state($user),
            ]);
        }

        if (! $practice) {
            app(\App\Services\HeartService::class)->loseOne($user);
        }

        return response()->json([
            'correct' => false,
            'correct_answer' => $card->answer,
            'hearts' => app(\App\Services\HeartService::class)->state($user),
        ]);
    }

    /**
     * Start a practice session (heart-friendly mode).
     */
    public function practice(Request $request, Lesson $lesson)
    {
        $user = $request->user();
        $progress = $user->progress ?? [];

        $progress[$lesson->id]['practice'] = [
            'checkpoint' => 0,
            'correct' => 0,
        ];
        $user->progress = $progress;
        $user->save();

        $cards = $lesson->cards()->orderBy('order')->orderBy('id')->get();

        return response()->json([
            'lesson' => ['id' => $lesson->id, 'name' => $lesson->name],
            'cards' => $cards->map(fn ($card) => [
                'id' => $card->id,
                'title' => $card->title,
                'content' => $card->content,
                'date' => $card->date,
            ])->values(),
            'total_cards' => $cards->count(),
        ]);
    }

    /**
     * Mark a practice session complete and restore a heart.
     */
    public function completePractice(Request $request, Lesson $lesson)
    {
        $user = $request->user();
        $progress = $user->progress ?? [];
        $practice = $progress[$lesson->id]['practice'] ?? null;

        if ($practice && (int) $practice['checkpoint'] >= $lesson->cards()->count()) {
            app(\App\Services\HeartService::class)->restore($user, config('gamification.hearts.practice_restores', 1));
            app(\App\Services\XpService::class)->add(
                $user,
                config('gamification.xp.practice_complete', 5),
                'practice_complete',
                ['lesson_id' => $lesson->id]
            );
            app(\App\Services\QuestService::class)->registerProgress($user, 'complete_practice', 1);
        }

        return response()->json([
            'message' => 'Practice finished',
            'hearts' => app(\App\Services\HeartService::class)->state($user),
        ]);
    }
}
