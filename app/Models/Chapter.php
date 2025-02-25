<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }
}
