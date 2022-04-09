<?php

namespace App\Models\Relationships;

use App\Models\Currency;

trait BelongsToCurrency
{
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function originCurrency()
    {
        return $this->belongsTo(Currency::class, 'origin_currency_id');
    }

    public function targetCurrency()
    {
        return $this->belongsTo(Currency::class, 'target_currency_id');
    }
}