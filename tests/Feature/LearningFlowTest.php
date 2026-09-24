<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Major;
use App\Models\User;
use Tests\ApiTestCase;

class LearningFlowTest extends ApiTestCase
{
    private function makeLesson(int $cardCount = 3): Lesson
    {
        $major = Major::create(['name' => ['en' => 'Medical Sciences', 'fa' => 'علوم پزشکی']]);
        $course = Course::create(['name' => ['en' => 'Anatomy', 'fa' => 'آناتومی'], 'major_id' => $major->id]);
        $lesson = Lesson::create([
            'name' => ['en' => 'Introduction', 'fa' => 'مقدمه'],
            'course_id' => $course->id,
        ]);

        for ($i = 0; $i < $cardCount; $i++) {
            Card::create([
                'title' => ['en' => "Card {$i}", 'fa' => "کارت {$i}"],
                'content' => ['en' => 'Question content', 'fa' => 'محتوا'],
                'answer' => ['en' => "answer-{$i}", 'fa' => "پاسخ-{$i}"],
                'order' => $i,
                'lesson_id' => $lesson->id,
            ]);
        }

        return $lesson;
    }

    public function test_start_lesson_returns_cards_without_answers(): void
    {
        $user = User::factory()->create();
        $lesson = $this->makeLesson(3);

        $response = $this->postJson("/api/lessons/{$lesson->id}/start", [], $this->authHeaders($user));

        $response->assertOk()
            ->assertJsonPath('total_cards', 3)
            ->assertJsonCount(3, 'cards');

        $this->assertArrayNotHasKey('answer', $response->json('cards.0'));
    }

    public function test_correct_answer_awards_xp(): void
    {
        $user = User::factory()->create(['xp' => 0, 'current_streak' => 0]);
        $lesson = $this->makeLesson(3);
        $card = $lesson->cards()->first();

        $response = $this->postJson("/api/cards/{$card->id}/answer", [
            'answer' => 'answer-0',
        ], $this->authHeaders($user));

        $response->assertOk()
            ->assertJsonPath('correct', true)
            ->assertJsonPath('earned_xp', 10);

        $this->assertEquals(10, $user->refresh()->xp);
    }

    public function test_wrong_answer_loses_a_heart(): void
    {
        $user = User::factory()->create(['heart' => 5]);
        $lesson = $this->makeLesson(3);
        $card = $lesson->cards()->first();

        $response = $this->postJson("/api/cards/{$card->id}/answer", [
            'answer' => 'wrong',
        ], $this->authHeaders($user));

        $response->assertOk()
            ->assertJsonPath('correct', false)
            ->assertJsonPath('correct_answer', 'answer-0');

        $this->assertEquals(4, $user->refresh()->heart);
    }

    public function test_completing_lesson_awards_completion_xp_once(): void
    {
        $user = User::factory()->create(['xp' => 0, 'current_streak' => 0]);
        $lesson = $this->makeLesson(2);
        $headers = $this->authHeaders($user);

        $this->postJson("/api/lessons/{$lesson->id}/start", [], $headers)->assertOk();

        foreach ($lesson->cards as $card) {
            $this->postJson("/api/cards/{$card->id}/answer", [
                'answer' => $card->getTranslation('answer', 'en'),
            ], $headers)->assertOk();
        }

        // 2 correct answers (10 each) + lesson completion (10) + perfect bonus (5) = 35
        $this->assertEquals(35, $user->refresh()->xp);

        $progress = $user->refresh()->progress;
        $this->assertTrue($progress[$lesson->id]['completed']);

        // Perfect lesson also awarded gems.
        $this->assertEquals(110, $user->refresh()->gems);

        // Replaying does not award completion XP again.
        $this->postJson("/api/lessons/{$lesson->id}/start", [], $headers)->assertOk();
        foreach ($lesson->cards as $card) {
            $this->postJson("/api/cards/{$card->id}/answer", [
                'answer' => $card->getTranslation('answer', 'en'),
            ], $headers)->assertOk();
        }

        // 2 more correct answers (20) but no extra completion XP.
        $this->assertEquals(55, $user->refresh()->xp);
    }

    public function test_practice_mode_does_not_cost_hearts(): void
    {
        $user = User::factory()->create(['heart' => 5]);
        $lesson = $this->makeLesson(2);
        $headers = $this->authHeaders($user);
        $card = $lesson->cards()->first();

        $response = $this->postJson("/api/cards/{$card->id}/answer", [
            'answer' => 'wrong',
            'practice' => true,
        ], $headers);

        $response->assertOk()
            ->assertJsonPath('correct', false);

        $this->assertEquals(5, $user->refresh()->heart);
    }
}
