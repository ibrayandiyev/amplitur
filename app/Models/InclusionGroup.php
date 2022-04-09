<?php

namespace App\Models;

use App\Models\Relationships\HasManyInclusions;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class InclusionGroup extends Model
{
    use HasManyInclusions,
        HasTranslations,
        HasDateLabels,
        HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    protected $translatable = [
        'name',
    ];
}
