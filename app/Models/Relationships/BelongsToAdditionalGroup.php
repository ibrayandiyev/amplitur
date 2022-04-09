<?php

namespace App\Models\Relationships;

use App\Models\AdditionalGroup;

trait BelongsToAdditionalGroup
{
    public function group()
    {
        return $this->belongsTo(AdditionalGroup::class, 'additional_group_id');
    }
}