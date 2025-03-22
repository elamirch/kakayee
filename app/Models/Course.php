<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function chapters() {
        return $this->hasMany(Chapter::class);
    }

    public function major() {
        return $this->belongsTo(Major::class)->withDefault();        
    }
}
