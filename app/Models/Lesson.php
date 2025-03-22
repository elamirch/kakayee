<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    public function cards() {
        return $this->hasMany(Card::class);
    }

    public function chapter() {
        return $this->belongsTo(Chapter::class)->withDefault();
    }
}
