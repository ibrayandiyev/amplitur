<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Models\Relationships\BelongsToCategory;
use App\Models\Relationships\HasManyImages;
use App\Models\Relationships\HasManyPackages;
use App\Models\Relationships\HasManyPrebookings;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasFlags;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Str;

class Event extends BaseModel
{
    use BelongsToCategory,
        HasManyPrebookings,
        HasManyPackages,
        HasManyImages,
        HasDateLabels,
        HasFlags,
        HasTranslations,
        HasFactory,
        Sluggable;

    protected $fillable = [
        'category_id',
        'name',
        'city',
        'country',
        'state',
        'description',
        'meta_keywords',
        'meta_description',
        'photo',
        'flags',
        'is_exclusive',
    ];

    protected $casts = [
        'flags' => 'array',
    ];

    public $translatable = [
        'description',
        'meta_keywords',
        'meta_description',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Check if event has duration of a range of dates
     *
     * @return  bool
     */
    public function hasRangeDatesDuration(): bool
    {
        if ($this->hasCategory()) {
            return $this->getDuration() == 'range-date';
        }

        return false;
    }

    /**
     * Check if event has duration of one day only
     *
     * @return  bool
     */
    public function hasOneDayDuration(): bool
    {
        if ($this->hasCategory()) {
            return $this->getDuration() == 'one-day';
        }

        return false;
    }

    /**
     * Get event duration type
     *
     * @return  string|null
     */
    public function getDuration(): ?string
    {
        if ($this->hasCategory()) {
            return $this->category->getFlag('DURATION');
        }

        return null;
    }

    /**
     * Check if event has a category
     *
     * @return  bool
     */
    public function hasCategory(): bool
    {
        return !empty($this->category);
    }

    /**
     * [hasAddress description]
     *
     * @return  bool    [return description]
     */
    public function hasAddress(): bool
    {
        return !empty($this->city) && !empty($this->country);
    }

    /**
     * [geTitle description]
     *
     * @return  string  [return description]
     */
    public function getTitle(): ?string
    {
        return $this->name;
    }

    /**
     * [getDescription description]
     *
     * @return  string  [return description]
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * [getName description]
     *
     * @return  string  [return description]
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * [getPrebookingUrl description]
     *
     * @return  string  [return description]
     */
    public function getPrebookingUrl(): ?string
    {
        return route('frontend.prebookings.create', [$this->id, $this->slug]);
    }

    /**
     * [getThumbnailUrl description]
     *
     * @return  string  [return description]
     */
    public function getThumbnailUrl(): ?string
    {
        $image = $this->images()
            ->where('is_default', true)
            ->first();

        if (empty($image)) {
            return url('frontend/images/img_nao_disponivel.png');
        }

        return $image->getThumbnailUrl();
    }
    /**
     * [getThumbnail2xUrl description]
     *
     * @return  string  [return description]
     */
    public function getThumbnail2xUrl(): ?string
    {
        $image = $this->images()
            ->where('is_default', true)
            ->first();

        if (empty($image)) {
            return url('frontend/images/img_nao_disponivel.png');
        }

        return $image->getThumbnail2xUrl();
    }


    /**
     * [getThumbnailAlt description]
     *
     * @return  string  [return description]
     */
    public function getThumbnailAlt(): ?string
    {
        return __('frontend.packages.seo.cover', ['name' => $this->name]);
    }

    /**
     * [getLocation description]
     *
     * @return  string  [return description]
     */
    public function getLocation(): ?string
    {
        if (!empty($this->city) && empty($this->country)) {
            $location = city($this->city);
        } else if (!empty($this->country) && empty($this->city)) {
            $location = country($this->country);
        } else {
            $location = city($this->city) . ' - ' . country($this->country);
        }

        return Str::upper($location);
    }

    /**
     * [getCity description]
     *
     * @return  string  [return description]
     */
    public function getCity(): ?string
    {
        return city($this->city);
    }

    /**
     * [getCountry description]
     *
     * @return  string  [return description]
     */
    public function getCountry(): ?string
    {
        return country($this->country);
    }


    /**
     * [getGallery description]
     *
     * @return  [type]  [return description]
     */
    public function getGallery()
    {
        return $this->images()->where('is_default', false)->get();
    }
}
