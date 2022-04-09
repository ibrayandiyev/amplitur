<?php

namespace App\Models;

use App\Models\Relationships\BelongsToInclusionGroup;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Inclusion extends Model
{
    use BelongsToInclusionGroup,
        HasTranslations,
        HasDateLabels,
        HasFactory;

    protected $fillable = [
        'inclusion_group_id',
        'name',
        'type',
        'is_exclusive',
        'allowed_companies'
    ];

    protected $translatable = [
        'name',  
    ];

    protected $casts = [
        'allowed_companies' => 'array'
    ];

    public function bustripRoutes()
    {
        return $this->morphedByMany(BustripRoute::class, 'inclusionable');
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
