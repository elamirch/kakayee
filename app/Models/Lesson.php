<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Lesson extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'notes'];

    protected $guarded = [];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class)->withDefault();
    }
}
