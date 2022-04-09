<?php

namespace App\Models\Relationships;

use App\Models\Category;

trait HasManyChildrenCategories
{
    public function children()
    {
        return $this->hasMany(Category::class, 'id', 'parent_id');
    }
}
