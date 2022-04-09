<?php

namespace App\Models\Relationships;

use App\Models\LongtripRoute;

trait HasManyLongtripRoutes
{
    public function longtripRoutes()
    {
        return $this->hasMany(LongtripRoute::class);
    }
}
