<?php

namespace App\Models;

use App\Models\Relationships\BelongsToPageGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use BelongsToPageGroup,
        HasTranslations,
        HasFactory;

    protected $fillable = [
        'page_group_id',
        'name',
        'title',
        'slug',
        'content',
        'og_title',
        'og_description',
        'og_keywords',
        'is_active',
    ];

    protected $translatable = [
        'name',
        'title',
        'slug',
        'content',
        'og_title',
        'og_description',
        'og_keywords',
    ];

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    public function getIsActiveLabelAttribute(): string
    {
        if ($this->isActive()) {
            return '<span class="label label-light-success">' . __('messages.active') . '</span>';
        }

        return '<span class="label label-light-success">' . __('messages.inactive') . '</span>';
    }
}
