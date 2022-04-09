<?php

namespace App\Models;

use App\Models\Relationships\BelongsToCurrency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyQuotation extends Model
{
    use BelongsToCurrency,
        HasFactory;

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

    /**
     * [lastUpdatedAt description]
     *
     * @return  [type]  [return description]
     */
    public function getLastUpdatedAtAttribute()
    {
        $lastUpdatedAt = $this->orderByDesc('updated_at')->limit(1)->select('updated_at')->first();

        return $lastUpdatedAt->updated_at->format('d/m/Y H:i');
    }

    /**
     * [isSame description]
     *
     * @return  bool    [return description]
     */
    public function isSame(): bool
    {
        return $this->origin_currency_id == $this->target_currency_id;
    }
}
