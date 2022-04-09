<?php

namespace App\Models;

use App\Models\Relationships\BelongsToCurrency;
use App\Models\Relationships\BelongsToPaymentMethod;
use App\Models\Relationships\BelongsToPromocodeGroup;
use App\Models\Relationships\HasManyBookings;
use App\Models\Traits\HasDateLabels;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Promocode extends Model
{
    use HasFactory,
        BelongsToPaymentMethod,
        BelongsToCurrency,
        BelongsToPromocodeGroup,
        HasManyBookings,
        HasDateLabels;


    protected $fillable = [
        'promocode_group_id',
        'payment_method_id',
        'name',
        'code',
        'currency_id',
        'discount_value',
        'stock',
        'usages',
        'max_installments',
        'cancels_cash_discount',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
    ];

    /**
     * [isExpired description]
     *
     * @return  [type]  [return description]
     */
    public function isExpired(): bool
    {
        return Carbon::today() >= $this->expires_at;
    }

    /**
     * [isAvailable description]
     *
     * @return  [type]  [return description]
     */
    public function isAvailable(): bool
    {
        return $this->usages < $this->stock;
    }

    /**
     * [cancelsCashDiscount description]
     *
     * @return  bool    [return description]
     */
    public function cancelsCashDiscount(): bool
    {
        return (bool) $this->cancels_cash_discount;
    }

    /**
     * [getCancelsCashDiscountLabelAttribute description]
     *
     * @return  string  [return description]
     */
    public function getCancelsCashDiscountLabelAttribute(): ?string
    {
        if ($this->cancelsCashDiscount()) {
            return __('messages.yes');
        }

        return __('messages.no');
    }

    /**
     * [getCode description]
     *
     * @return  [type]  [return description]
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * [getName description]
     *
     * @return  [type]  [return description]
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * [getDiscount description]
     *
     * @return  [type]  [return description]
     */
    public function getDiscount(): float
    {
        return number_format($this->discount_value, 2);
    }
}
