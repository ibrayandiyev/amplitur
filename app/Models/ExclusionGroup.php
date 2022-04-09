<?php

namespace App\Models;

use App\Models\Relationships\HasManyExclusions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ExclusionGroup extends Model
{
    use HasManyExclusions,
        HasTranslations,
        HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    protected $translatable = [
        'name',
    ];
}
