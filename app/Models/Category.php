<?php

namespace App\Models;

use App\Enums\CategoryType;
use App\Models\Relationships\BelongsToParentCategory;
use App\Models\Relationships\HasManyChildrenCategories;
use App\Models\Relationships\HasManyEvents;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasFlags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasManyChildrenCategories,
        BelongsToParentCategory,
        HasManyEvents,
        HasDateLabels,
        HasFlags,
        HasTranslations,
        HasFactory;

    public const TYPE_PACKAGE = CategoryType::PACKAGE;
    public const TYPE_EVENT = CategoryType::EVENT;
    public const TYPE_HOTEL = CategoryType::HOTEL;
    public const TYPE_OTHER = CategoryType::OTHER;

    protected $fillable = [
        'parent_id',
        'slug',
        'name',
        'description',
        'type',
        'flags',
    ];

    protected $casts = [
        'flags' => 'array',
    ];

    protected $translatable = [
        'slug',
        'name',
        'description',
    ];


    /**
     * Get well formatted type label
     *
     * @return  string
     */
    public function getTypeLabelAttribute(): ?string
    {
        if ($this->type == self::TYPE_PACKAGE) {
            return '<span class="label label-light-success">' . __('resources.categories.model.types.package') . '</span>';
        }

        if ($this->type == self::TYPE_EVENT) {
            return '<span class="label label-light-primary">' . __('resources.categories.model.types.event') . '</span>';
        }

        if ($this->type == self::TYPE_OTHER) {
            return '<span class="label label-light-inverse">' . __('resources.categories.model.types.other') . '</span>';
        }

        return $this->type;
    }

    public function getTypeTextAttribute(): ?string
    {
        if ($this->type == self::TYPE_PACKAGE) {
            return __('resources.categories.model.types.package');
        }

        if ($this->type == self::TYPE_EVENT) {
            return __('resources.categories.model.types.event');
        }

        if ($this->type == self::TYPE_HOTEL) {
            return __('resources.categories.model.types.hotel');
        }

        if ($this->type == self::TYPE_OTHER) {
            return __('resources.categories.model.types.other');
        }

        return $this->type;
    }
}
