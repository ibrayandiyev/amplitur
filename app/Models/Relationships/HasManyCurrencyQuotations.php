<?php

namespace App\Models\Relationships;

use App\Models\CurrencyQuotation;

trait HasManyCurrencyQuotations
{
    public function quotations()
    {
        return $this->hasMany(CurrencyQuotation::class, 'origin_currency_id');
    }
}