<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HotelStructure extends Model
{
    use HasTranslations,
        HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $translatable = [
        'name',
    ];
}
