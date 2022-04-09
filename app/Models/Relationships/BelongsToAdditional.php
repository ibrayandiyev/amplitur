<?php

namespace App\Models\Relationships;

use App\Models\Additional;

trait BelongsToAdditional
{
    public function additional()
    {
        return $this->belongsTo(Additional::class);
    }
}