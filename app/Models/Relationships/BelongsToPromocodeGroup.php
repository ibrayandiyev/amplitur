<?php

namespace App\Models\Relationships;

use App\Models\PromocodeGroup;

trait BelongsToPromocodeGroup
{
    public function group()
    {
        return $this->belongsTo(PromocodeGroup::class);
    }
}