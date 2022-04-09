<?php

namespace App\Models\Relationships;

use App\Models\ShuttleRoute;

trait BelongsToShuttleRoute
{
    public function shuttleRoute()
    {
        return $this->belongsTo(ShuttleRoute::class);
    }
}