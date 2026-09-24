<?php

namespace Database\Seeders;

use App\Models\Quest;
use Illuminate\Database\Seeder;

class QuestSeeder extends Seeder
{
    public function run(): void
    {
        $quests = [
            [
                'key' => 'earn_xp',
                'type' => 'daily',
                'target' => 50,
                'xp_reward' => 10,
                'gem_reward' => 5,
                'title' => ['en' => 'Earn 50 XP today', 'fa' => 'امروز ۵۰ ایکس‌پی کسب کن'],
            ],
            [
                'key' => 'complete_lesson',
                'type' => 'daily',
                'target' => 1,
                'xp_reward' => 15,
                'gem_reward' => 10,
                'title' => ['en' => 'Complete one lesson', 'fa' => 'یک درس را کامل کن'],
            ],
            [
                'key' => 'answer_correct',
                'type' => 'daily',
                'target' => 20,
                'xp_reward' => 20,
                'gem_reward' => 10,
                'title' => ['en' => 'Answer 20 questions correctly', 'fa' => 'به ۲۰ سؤال درست پاسخ بده'],
            ],
            [
                'key' => 'earn_xp',
                'type' => 'weekly',
                'target' => 500,
                'xp_reward' => 50,
                'gem_reward' => 25,
                'title' => ['en' => 'Earn 500 XP this week', 'fa' => 'این هفته ۵۰۰ ایکس‌پی کسب کن'],
            ],
            [
                'key' => 'complete_practice',
                'type' => 'weekly',
                'target' => 3,
                'xp_reward' => 30,
                'gem_reward' => 10,
                'title' => ['en' => 'Finish 3 practice sessions', 'fa' => '۳ جلسه تمرین را به پایان برسان'],
            ],
        ];

        foreach ($quests as $quest) {
            Quest::create($quest);
        }
    }
}
