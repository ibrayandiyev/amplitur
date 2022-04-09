<?php

namespace App\Models\Relationships;

use App\Models\SaleCoefficient;

trait BelongsToSaleCoefficient
{
    public function saleCoefficient()
    {
        return $this->belongsTo(SaleCoefficient::class);
    }
}