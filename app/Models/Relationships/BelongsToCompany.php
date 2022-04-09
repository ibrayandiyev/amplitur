<?php

namespace App\Models\Relationships;

use App\Models\Company;

trait BelongsToCompany
{
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}