<?php

namespace App\Models\Relationships;

use App\Models\Currency;

trait BelongsToCurrencyOrigin
{
    public function currencyOrigin()
    {
        return $this->belongsTo(Currency::class, 'currency_origin_id');
    }
}