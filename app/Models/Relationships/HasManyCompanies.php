<?php

namespace App\Models\Relationships;

use App\Models\Company;

trait HasManyCompanies
{
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
