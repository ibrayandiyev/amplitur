<?php

namespace App\Repositories\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait HasImageUpload
{
    /**
     * [handleImageUpload description]
     *
     * @param   Model  $resource    [$resource description]
     * @param   array  $attributes  [$attributes description]
     *
     * @return  array               [return description]
     */
    public function handleImageUpload(Model $resource, array $attributes): array
    {
        if (empty($attributes['uploaded_images'])) {
            return $attributes;
        }

        $images = $this->storeImages($attributes['uploaded_images']);

        $resource->images = array_merge($images, $resource->images ?? []);

        $resource->save();

        return $attributes;
    }

    /**
     * [storeImage description]
     *
     * @param   UploadedFile  $file  [$file description]
     *
     * @return  [type]               [return description]
     */
    public function storeImage(UploadedFile $file): ?array
    {
        $fileName = str_replace('-', '', Str::uuid()) . '.' . $file->extension();
        $fileName = Storage::disk('images')->putFileAs(imagePath(), $file, $fileName);

        $image = [
            'path' => $fileName,
            'subtitle' => null,
            'order' => null,
        ];

        return $image;
    }

    /**
     * [storeImages description]
     *
     * @param   array  $files  [$files description]
     *
     * @return  [type]         [return description]
     */
    public function storeImages(array $files): array
    {
        $images = [];

        foreach ($files as $file) {
            $images[] = $this->storeImage($file);
        }

        return $images;
    }

    /**
     * [deleteImage description]
     *
     * @param   Model   $resource  [$resource description]
     * @param   string  $filePath  [$filePath description]
     *
     * @return  [type]             [return description]
     */
    public function deleteImage(Model $resource, string $filePath)
    {
        $images = $resource->images;

        foreach ($resource->images as $key => $image) {
            if ($image['path'] == $filePath) {
                unset($images[$key]);
                $resource->images = $images;
                $resource->save();
            }
        }
    }
}