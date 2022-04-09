<?php

namespace App\Models\Relationships;

use App\Models\Page;

trait HasManyPages
{
    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
