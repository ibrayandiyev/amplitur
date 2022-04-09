<?php

namespace App\Models;

use App\Models\Relationships\HasManyHotelAccommodations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HotelAccommodationType extends Model
{
    use HasTranslations,
        HasManyHotelAccommodations,
        HasFactory;

    protected $fillable = [
        'name',
        'capacity',
    ];

    protected $translatable = [
        'name',
    ];
}
