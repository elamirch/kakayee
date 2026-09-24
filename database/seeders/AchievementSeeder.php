<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'key' => 'first_lesson',
                'title' => ['en' => 'First Steps', 'fa' => 'اولین قدم‌ها'],
                'description' => ['en' => 'Complete your first lesson', 'fa' => 'اولین درس خود را کامل کن'],
                'condition' => ['type' => 'lessons_completed', 'value' => 1],
                'xp_reward' => 20,
                'gem_reward' => 10,
            ],
            [
                'key' => 'lessons_10',
                'title' => ['en' => 'Scholar', 'fa' => 'دانش‌آموز'],
                'description' => ['en' => 'Complete 10 lessons', 'fa' => '۱۰ درس را کامل کن'],
                'condition' => ['type' => 'lessons_completed', 'value' => 10],
                'xp_reward' => 50,
                'gem_reward' => 25,
            ],
            [
                'key' => 'streak_3',
                'title' => ['en' => 'On Fire', 'fa' => 'در آتش'],
                'description' => ['en' => 'Keep a 3-day streak', 'fa' => 'سه روز پیاپی تمرین کن'],
                'condition' => ['type' => 'current_streak', 'value' => 3],
                'xp_reward' => 30,
            ],
            [
                'key' => 'streak_30',
                'title' => ['en' => 'Unstoppable', 'fa' => 'توقف‌ناپذیر'],
                'description' => ['en' => 'Keep a 30-day streak', 'fa' => 'سی روز پیاپی تمرین کن'],
                'condition' => ['type' => 'current_streak', 'value' => 30],
                'xp_reward' => 200,
                'gem_reward' => 100,
            ],
            [
                'key' => 'xp_500',
                'title' => ['en' => 'Learner', 'fa' => 'یادگیرنده'],
                'description' => ['en' => 'Earn 500 total XP', 'fa' => 'مجموعاً ۵۰۰ ایکس‌پی کسب کن'],
                'condition' => ['type' => 'total_xp', 'value' => 500],
                'xp_reward' => 25,
                'gem_reward' => 25,
            ],
            [
                'key' => 'xp_5000',
                'title' => ['en' => 'Expert', 'fa' => 'متخصص'],
                'description' => ['en' => 'Earn 5000 total XP', 'fa' => 'مجموعاً ۵۰۰۰ ایکس‌پی کسب کن'],
                'condition' => ['type' => 'total_xp', 'value' => 5000],
                'xp_reward' => 250,
                'gem_reward' => 150,
            ],
            [
                'key' => 'correct_100',
                'title' => ['en' => 'Sharpshooter', 'fa' => 'تیزانداز'],
                'description' => ['en' => 'Answer 100 questions correctly', 'fa' => 'به ۱۰۰ سؤال درست پاسخ بده'],
                'condition' => ['type' => 'correct_answers', 'value' => 100],
                'gem_reward' => 50,
            ],
            [
                'key' => 'perfect_10',
                'title' => ['en' => 'Perfectionist', 'fa' => 'کمال‌گرا'],
                'description' => ['en' => 'Complete 10 perfect lessons', 'fa' => '۱۰ درس را بدون اشتباه کامل کن'],
                'condition' => ['type' => 'perfect_lessons', 'value' => 10],
                'xp_reward' => 100,
                'gem_reward' => 50,
            ],
            [
                'key' => 'practice_25',
                'title' => ['en' => 'Dedicated', 'fa' => 'متعهد'],
                'description' => ['en' => 'Finish 25 practice sessions', 'fa' => '۲۵ جلسه تمرین را به پایان برسان'],
                'condition' => ['type' => 'practice_completed', 'value' => 25],
                'xp_reward' => 80,
                'gem_reward' => 40,
            ],
            [
                'key' => 'gems_500',
                'title' => ['en' => 'Rich', 'fa' => 'ثروتمند'],
                'description' => ['en' => 'Hold 500 gems', 'fa' => '۵۰۰ جواهر در اختیار داشته باش'],
                'condition' => ['type' => 'gems', 'value' => 500],
                'xp_reward' => 50,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
