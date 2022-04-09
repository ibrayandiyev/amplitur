<?php

namespace App\Models\Relationships;

use App\Models\Category;

trait BelongsToCategory
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}