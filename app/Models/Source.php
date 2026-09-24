<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Source extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['source'];

    protected $guarded = [];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
