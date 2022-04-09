<?php

namespace App\Models\Relationships;

use App\Models\LongtripRoute;

trait BelongsToLongtripRoute
{
    public function longtripRoute()
    {
        return $this->belongsTo(LongtripRoute::class);
    }
}