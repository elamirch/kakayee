<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Card extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['title', 'content', 'answer', 'explanation'];

    protected $guarded = [];

    protected $hidden = ['answer'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class)->withDefault();
    }

    public function source()
    {
        return $this->belongsTo(Source::class)->withDefault();
    }
}
