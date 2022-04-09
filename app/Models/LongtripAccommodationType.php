<?php

namespace App\Models;

use App\Models\Relationships\HasManyLongtripAccommodations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LongtripAccommodationType extends Model
{
    use HasTranslations,
        HasManyLongtripAccommodations,
        HasFactory;

    protected $fillable = [
        'name',
        'capacity',
    ];

    protected $translatable = [
        'name',
    ];
}
