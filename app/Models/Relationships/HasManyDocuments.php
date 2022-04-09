<?php

namespace App\Models\Relationships;

use App\Models\Document;

trait HasManyDocuments
{
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
