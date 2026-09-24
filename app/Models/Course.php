<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Course extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];

    protected $guarded = [];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class)->withDefault();
    }
}
