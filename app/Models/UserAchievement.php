<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    protected $guarded = [];

    protected $casts = [
        'unlocked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
