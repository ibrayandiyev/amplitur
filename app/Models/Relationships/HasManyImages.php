<?php

namespace App\Models\Relationships;

use App\Models\Image;

trait HasManyImages
{
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}