<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Major extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];

    protected $guarded = [];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
