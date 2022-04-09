<?php

namespace App\Models\Relationships;

use App\Models\LongtripBoardingLocation;

trait HasManyLongtripBoardingLocations
{
    public function longtripBoardingLocations()
    {
        return $this->hasMany(LongtripBoardingLocation::class);
    }
}
