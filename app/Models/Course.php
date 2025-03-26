<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    public function major() {
        return $this->belongsTo(Major::class)->withDefault();        
    }
}
