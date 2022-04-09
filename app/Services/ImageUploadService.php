<?php

namespace App\Services;

use App\Models\Image;
use App\Repositories\ImageRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InterventionImage;

class ImageUploadService
{
    /**
     * @var ImageRepository
     */
    protected $repository;

    public function __construct(ImageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * [upload description]
     *
     * @param   array|UploadedFile $files
     * @param   Image  $image
     *
     * @return  Image|array|null
     */
    public function upload($files, Image $image)
    {
        if (is_iterable($files)) {
            return $this->uploadMultipleFiles($files, $image);
        }

        return $this->uploadSingleFile($files, [], $image);
    }

    /**
     * [uploadSingleFile description]
     *
     * @param   UploadedFile  $file   [$file description]
     * @param   Image         $image  [$image description]
     *
     * @return  Image                 [return description]
     */
    public function uploadSingleFile(UploadedFile $file, array $attributes = [], Image $image): ?Image
    {
        $filename = !empty($image->title) ? $image->title : $file->getClientOriginalName();
        $filename = str_replace('.' . $file->getClientOriginalExtension(), '', $filename);
        $filename = Str::slug(uniqid() . '-' . $filename);
        $filename = $filename . '.' . $file->getClientOriginalExtension();
        $filename = Storage::disk('images')->putFileAs(imagePath() . 'images', $file, $filename, 'public');
        $filename = str_replace(imagePath(), '', $filename);

        $image = $this->repository->update($image, [
            'path' => 'images',
            'filename' => str_replace('images/', '', $filename),
        ]);

        $this->processImage($image, $filename, $attributes, true);

        return $image;
    }

    /**
     * [updateSingleFile description]
     *
     * @param   UploadedFile  $file        [$file description]
     * @param   array         $attributes  [$attributes description]
     * @param   Image         $image       [$image description]
     *
     * @return  Image                      [return description]
     */
    public function updateSingleFile(?UploadedFile $file, array $attributes = [], Image $image): ?Image
    {
        if (empty($file)) {
            $this->processImage($image, $image->filename, $attributes);
            return $image;
        }
    
        if (Storage::disk('images')->exists(imagePath() . $image->filename)) {
            Storage::disk('images')->delete(imagePath() . $image->filename);
        }

        Storage::disk('images')->putFileAs(imagePath(), $file, $image->filename);

        $this->processImage($image, $image->filename, $attributes);

        return $image;
    }

    /**
     * [uploadMultipleFiles description]
     *
     * @param   array  $files   [$files description]
     * @param   array  $images  [$image description]
     *
     * @return  array          [return description]
     */
    public function uploadMultipleFiles($files, Image $image): array
    {
        $images = [];

        foreach ($files as $file) {
            $image[] = $this->uploadSingleFile($file, [], $image);
        }

        return $images;
    }

    /**
     * [processImage description]
     *
     * @param   string  $filename    [$filename description]
     * @param   array   $attributes  [$attributes description]
     *
     * @return  []                   [return description]
     */
    protected function processImage(Image $image, string $filename, array $attributes = [], bool $isNew = false)
    {
        $path = $image->type == 'slideshow' ? 'images/slideshow-' : 'images/cropped-';
        $originalPath = $isNew ? image($filename) : image($path . $filename);
        $filename = str_replace('original-', '', $filename);

        if ($image->type != 'slideshow') {
            $originalImage = InterventionImage::make($originalPath);
            $thumbnail = InterventionImage::make($originalPath);
            $thumbnail2x = InterventionImage::make($originalPath);
            $mini = InterventionImage::make($originalPath);

            $originalImage = $originalImage
                ->rotate($attributes['rotate'])
                ->crop($attributes['width'], $attributes['height'], $attributes['x'], $attributes['y']);

            $thumbnail = $thumbnail
                ->rotate($attributes['rotate'])
                ->crop($attributes['width'], $attributes['height'], $attributes['x'], $attributes['y'])
                ->fit(356, 180);

            $thumbnail2x = $thumbnail2x
                ->rotate($attributes['rotate'])
                ->crop($attributes['width'], $attributes['height'], $attributes['x'], $attributes['y'])
                ->fit(712, 360);

            $mini = $mini
                ->rotate($attributes['rotate'])
                ->crop($attributes['width'], $attributes['height'], $attributes['x'], $attributes['y'])
                ->fit(150);

            $filename = str_replace('images/', '', $filename);

            Storage::disk('images')->put(imagePath() . 'images/cropped-' . $filename, $originalImage->stream('png', 60), 'public');
            Storage::disk('images')->put(imagePath() . 'images/thumbnail-' . $filename, $thumbnail->stream('png', 60), 'public');
            Storage::disk('images')->put(imagePath() . 'images/thumbnail2x-' . $filename, $thumbnail2x->stream('png', 60), 'public');
            Storage::disk('images')->put(imagePath() . 'images/mini-' . $filename, $mini->stream('png', 60), 'public');
        
            return;
        }

        $slideshow = InterventionImage::make($originalPath);

        $filename = str_replace('images/', '', $filename);

        $slideshow = $slideshow
            ->rotate($attributes['rotate'])
            ->crop($attributes['width'], $attributes['height'], $attributes['x'], $attributes['y'])
            ->fit(1140, 400);

        Storage::disk('images')->put(imagePath() . 'images/slideshow-' . $filename, $slideshow->stream('png', 60), 'public');

        return;
    }
}