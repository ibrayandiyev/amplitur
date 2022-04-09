<?php

namespace App\Models\Relationships;

use App\Models\CurrencyQuotationHistory;

trait HasManyCurrencyQuotationHistory
{
    public function quotationHistory()
    {
        return $this->hasMany(CurrencyQuotationHistory::class, 'origin_currency_id');
    }
}