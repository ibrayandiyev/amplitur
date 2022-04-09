<?php

namespace App\Repositories;

use App\Models\Image;
use App\Services\ImageUploadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ImageRepository extends Repository
{
    /**
     * @var ImageUploadService
     */
    protected $service;

    public function __construct(Image $model)
    {
        $this->model = $model;
    }

    /**
     * [listPackagesAndEvents description]
     *
     * @return  Collection[return description]
     */
    public function listPackagesAndEvents(): Collection
    {
        $images = $this->model
            ->whereNotNull('package_id')
            ->orWhereNotNull('event_id')
            ->get();

        return $images;
    }

    /**
     * [getSlideshowImages description]
     *
     * @return  Collection[return description]
     */
    public function getSlideshowImages(?string $language = null): Collection
    {
        $query = $this->model->where('type', 'slideshow');

        if (!empty($language)) {
            $query = $query->where('language', $language);
        }

        $images = $query->get();

        return $images;
    }

    public function onAfterStore(Model $resource, array $attributes): Model
    {
        if (!empty($attributes['image'])) {
            $resource = app(ImageUploadService::class)->uploadSingleFile($attributes['image'], $attributes['image_attributes'], $resource);
        }

        $this->handleDefaultImage($attributes, $resource);
        
        return $resource;
    }

    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        if (!empty($attributes['image_attributes'])) {
            $resource = app(ImageUploadService::class)->updateSingleFile($attributes['image'], $attributes['image_attributes'], $resource);
        }

        $this->handleDefaultImage($attributes, $resource);
        
        return $resource;
    }

    public function handleDefaultImage(array $attributes, Model $resource)
    {
        if(!$resource->isDefault()) {
            return;
        }

        if ($resource->isEvent()) {
            $images = $this->model->where('event_id', $resource->event_id)->where('id', '!=', $resource->id)->get();

            if (!empty($images)) {
                foreach ($images as $image) {
                    $image->is_default = 0;
                    $image->save();
                }
            }
        }

        if ($resource->isPackage()) {
            $images = $this->model->where('package_id', $resource->package_id)->where('id', '!=', $resource->id)->get();

            if (!empty($images)) {
                foreach ($images as $image) {
                    $image->is_default = 0;
                    $image->save();
                }
            }
        }

        if ($resource->isOffer()) {
            $images = $this->model->where('offer_id', $resource->offer_id)->where('id', '!=', $resource->id)->get();

            if (!empty($images)) {
                foreach ($images as $image) {
                    $image->is_default = 0;
                    $image->save();
                }
            }
        }
    }
}