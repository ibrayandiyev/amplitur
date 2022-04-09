<?php

namespace App\Models\Relationships;

use App\Models\Category;

trait HasManyCategories
{
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
