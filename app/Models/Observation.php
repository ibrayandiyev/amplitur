<?php

namespace App\Models;

use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Observation extends Model
{
    use HasTranslations,
        HasDateLabels,
        HasFactory;

    protected $fillable = [
        'name',
        'type',
        'is_exclusive',
    ];

    protected $translatable = [
        'name',
    ];

    public function bustripRoutes()
    {
        return $this->morphedByMany(BustripRoute::class, 'observationables');
    }

    public function isExclusive()
    {
        return $this->is_exclusive == 1;
    }

    public function getIsExclusiveLabelAttribute(): ?string
    {
        return $this->isExclusive() ? __('messages.yes') : __('messages.no');
    }
}
