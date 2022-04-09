<?php

namespace App\Models\Relationships;

use App\Models\InclusionGroup;

trait BelongsToInclusionGroup
{
    public function group()
    {
        return $this->belongsTo(InclusionGroup::class, 'inclusion_group_id');
    }
}