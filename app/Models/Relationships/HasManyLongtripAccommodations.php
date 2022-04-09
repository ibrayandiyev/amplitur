<?php

namespace App\Models\Relationships;

use App\Models\LongtripAccommodation;

trait HasManyLongtripAccommodations
{
    public function longtripAccommodations()
    {
        return $this->hasMany(LongtripAccommodation::class);
    }
}
