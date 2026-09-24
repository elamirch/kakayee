<?php

namespace App\Listeners;

use App\Events\XpAwarded;
use App\Services\QuestService;

class RegisterQuestProgress
{
    public function __construct(protected QuestService $questService) {}

    public function handle(XpAwarded $event): void
    {
        $this->questService->registerProgress($event->user, 'earn_xp', $event->amount);
    }
}
