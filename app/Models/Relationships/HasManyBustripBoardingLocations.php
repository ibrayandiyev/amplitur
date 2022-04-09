<?php

namespace App\Models\Relationships;

use App\Models\BustripBoardingLocation;

trait HasManyBustripBoardingLocations
{
    public function bustripBoardingLocations()
    {
        return $this->hasMany(BustripBoardingLocation::class);
    }
}
