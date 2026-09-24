<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Quest extends Model
{
    use HasTranslations;

    public $translatable = ['title'];

    protected $guarded = [];

    public function userQuests()
    {
        return $this->hasMany(UserQuest::class);
    }
}
