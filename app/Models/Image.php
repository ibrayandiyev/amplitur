<?php

namespace App\Models;

use App\Enums\Language;
use App\Models\Relationships\BelongsToEvent;
use App\Models\Relationships\BelongsToOffer;
use App\Models\Relationships\BelongsToPackage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use BelongsToEvent,
        BelongsToPackage,
        BelongsToOffer,
        HasFactory;

    protected $fillable = [
        'package_id',
        'event_id',
        'offer_id',
        'provider_id',
        'type',
        'path',
        'filename',
        'title',
        'subtitle',
        'language',
        'link',
        'is_default',
    ];

    public function isDefault(): bool
    {
        return (bool) $this->is_default;
    }

    public function isEvent(): bool
    {
        return !is_null($this->event_id);
    }

    public function isPackage(): bool
    {
        return !is_null($this->package_id);
    }

    public function isOffer(): bool
    {
        return !is_null($this->offer_id);
    }

    public function getUrl(): ?string
    {
        if ($this->type == 'slideshow') {
            return image($this->path . '/slideshow-' . $this->filename);
        }

        return image($this->path . '/cropped-' . $this->filename);
    }

    public function getThumbnailUrl(): ?string
    {
        return image($this->path . '/thumbnail-' . $this->filename);
    }

    public function getThumbnail2xUrl(): ?string
    {
        return image($this->path . '/thumbnail2x-' . $this->filename);
    }

    public function getIsDefaultLabel(): ?string
    {
        if ($this->isDefault()) {
            return '<span class="label label-success"><i class="fa fa-check"></i></span>';
        }

        return null;
    }

    public function getFlagLabel(): ?string
    {
        if ($this->language == Language::PORTUGUESE) {
            return '<i class="flag-icon flag-icon-br"></i>';
        }

        if ($this->language == Language::ENGLISH) {
            return '<i class="flag-icon flag-icon-gb"></i>';
        }

        if ($this->language == Language::SPANISH) {
            return '<i class="flag-icon flag-icon-es"></i>';
        }

        return '';
    }
}
