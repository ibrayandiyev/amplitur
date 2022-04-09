<?php

namespace App\Models\Relationships;

use App\Models\BustripRoute;

trait HasManyBustripRoutes
{
    public function bustripRoutes()
    {
        return $this->hasMany(BustripRoute::class);
    }
}
