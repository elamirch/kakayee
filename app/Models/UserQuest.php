<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQuest extends Model
{
    protected $guarded = [];

    protected $casts = [
        'completed_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quest()
    {
        return $this->belongsTo(Quest::class);
    }
}
