<?php

namespace App\Models;

use App\Models\Relationships\HasManyPages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PageGroup extends Model
{
    use HasManyPages,
        HasTranslations,
        HasFactory;

    protected $fillable = [
        'name',
        'order',
    ];

    protected $translatable = [
        'name',
    ];
}
