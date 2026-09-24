<?php

namespace App\Listeners;

use App\Events\XpAwarded;
use App\Services\AchievementService;

class CheckAchievements
{
    public function __construct(protected AchievementService $achievementService) {}

    public function handle(XpAwarded $event): void
    {
        $this->achievementService->check($event->user);
    }
}
