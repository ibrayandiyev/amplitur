<?php

namespace App\Models\Relationships;

use App\Models\ExclusionGroup;

trait BelongsToExclusionGroup
{
    public function group()
    {
        return $this->belongsTo(ExclusionGroup::class, 'exclusion_group_id');
    }
}