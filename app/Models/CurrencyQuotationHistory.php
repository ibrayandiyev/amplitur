<?php

namespace App\Models;

use App\Models\Relationships\BelongsToCurrency;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyQuotationHistory extends Model
{
    use BelongsToCurrency,
        HasDateLabels,
        HasFactory;

    protected $table = 'currency_quotation_history';

    protected $fillable = [
        'origin_currency_id',
        'target_currency_id',
        'quotation',
        'spread',
    ];

    public function getDecimalSpreadAttribute()
    {
        return number_format($this->spread, 2);
    }

    public function getSpreadedQuotationAttribute()
    {
        return spread($this->quotation, $this->spread, $this->targetCurrency->code, $this->originCurrency->code);
    }
}
