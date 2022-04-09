<?php

namespace App\Models\Relationships;

use App\Models\BustripRoute;

trait BelongsToBustripRoute
{
    public function bustripRoute()
    {
        return $this->belongsTo(BustripRoute::class);
    }
}