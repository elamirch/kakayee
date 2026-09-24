<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Achievement extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'description'];

    protected $guarded = [];

    protected $casts = [
        'condition' => 'array',
    ];

    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }
}
