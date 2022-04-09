<?php

namespace App\Models\Relationships;

use App\Models\ShuttleBoardingLocation;

trait HasManyShuttleBoardingLocations
{
    public function shuttleBoardingLocations()
    {
        return $this->hasMany(ShuttleBoardingLocation::class);
    }
}
