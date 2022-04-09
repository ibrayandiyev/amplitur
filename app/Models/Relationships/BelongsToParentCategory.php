<?php

namespace App\Models\Relationships;

use App\Models\Category;

trait BelongsToParentCategory
{
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}