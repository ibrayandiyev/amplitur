<?php

namespace App\Models\Relationships;

use App\Models\PageGroup;

trait BelongsToPageGroup
{
    public function pageGroup()
    {
        return $this->belongsTo(PageGroup::class);
    }
}