<?php

namespace App\Models\Relationships;

use App\Models\ShuttleRoute;

trait HasManyShuttleRoutes
{
    public function shuttleRoutes()
    {
        return $this->hasMany(ShuttleRoute::class);
    }
}
