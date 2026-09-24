<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class XpAwarded
{
    use Dispatchable;

    public function __construct(
        public User $user,
        public int $amount,
        public string $reason,
        public array $metadata = [],
    ) {}
}
