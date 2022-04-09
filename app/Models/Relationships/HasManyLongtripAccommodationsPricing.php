<?php

namespace App\Models\Relationships;

use App\Models\LongtripAccommodationsPricing;

trait HasManyLongtripAccommodationsPricing
{
    public function longtripAccommodationsPricings()
    {
        return $this->hasMany(LongtripAccommodationsPricing::class);
    }
}
