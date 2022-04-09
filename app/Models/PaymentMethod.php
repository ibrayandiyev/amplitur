<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Enums\PaymentMethod as EnumsPaymentMethod;
use App\Models\Relationships\BelongsToManyPackages;
use App\Models\Relationships\HasManyBookings;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends BaseModel
{
    use HasManyBookings,
        BelongsToManyPackages,
        HasFactory;

    protected $fillable = [
        'category',
        'type',
        'name',
        'code',
        'max_installments',
        'first_installment_billet',
        'offline',
        'komerci',
        'rede',
        'cielo',
        'shopline',
        'paypal',
        'bradesco',
    ];

    protected $casts = [
        'first_installment_billet' => 'boolean',
        'offline' => 'boolean',
        'komerci' => 'boolean',
        'rede' => 'boolean',
        'cielo' => 'boolean',
        'shopline' => 'boolean',
        'paypal' => 'boolean',
        'bradesco' => 'boolean',
    ];

    const IN_CASH_DISCOUNT = 0.05;

    /**
     * [isCreditCard description]
     *
     * @return  bool    [return description]
     */
    public function isCreditCard(): bool
    {
        switch($this->code){
            case EnumsPaymentMethod::PM_CODE_CREDIT_CARD:
            case EnumsPaymentMethod::PM_CODE_CREDIT_CARD_RECURRENCE:
                return true;
        }
        return false;
    }

    /**
     * [isNational description]
     *
     * @return  bool    [return description]
     */
    public function isNational(): bool
    {
        return $this->category == 'national';
    }

    /**
     * [isNational description]
     *
     * @return  bool    [return description]
     */
    public function isOffline(): bool
    {
        return (bool) $this->offline;
    }

    /**
     * [isInternational description]
     *
     * @return  bool    [return description]
     */
    public function isInternational(): bool
    {
        return $this->category == 'international';
    }

    /**
     * [isCredit description]
     *
     * @return  bool    [return description]
     */
    public function isCredit(): bool
    {
        return $this->type == 'credit';
    }

    /**
     * [isCredit description]
     *
     * @return  bool    [return description]
     */
    public function isDebit(): bool
    {
        return $this->type == 'debit';
    }

    /**
     * [isCredit description]
     *
     * @return  bool    [return description]
     */
    public function isBillet(): bool
    {
        return $this->type == 'billet';
    }

    /**
     * [getDefaultProcessor description]
     *
     * @return  string  [return description]
     */
    public function getDefaultProcessor(): ?string
    {
        if ($this->offline) {
            return 'offline';
        }

        if ($this->komerci) {
            return 'komerci';
        }

        if ($this->rede) {
            return 'rede';
        }

        if ($this->cielo) {
            return 'cielo';
        }

        if ($this->shopline) {
            return 'shopline';
        }

        if ($this->paypal) {
            return 'paypal';
        }

        if ($this->bradesco) {
            return 'bradesco';
        }
    }

    /**
     * [firstInstallmentMustBeBillet description]
     *
     * @return  bool    [return description]
     */
    public function firstInstallmentMustBeBillet(): bool
    {
        return (bool) $this->first_installment_billet;
    }

    /**
     * [availableOffline description]
     *
     * @return  bool    [return description]
     */
    public function availableOffline(): bool
    {
        return (bool) $this->offline;
    }

    /**
     * [availableKomerci description]
     *
     * @return  bool    [return description]
     */
    public function availableKomerci(): bool
    {
        return (bool) $this->komerci;
    }

    /**
     * [availableRede description]
     *
     * @return  bool    [return description]
     */
    public function availableRede(): bool
    {
        return (bool) $this->rede;
    }

    /**
     * [availableCielo description]
     *
     * @return  bool    [return description]
     */
    public function availableCielo(): bool
    {
        return (bool) $this->cielo;
    }

    /**
     * [availableShopline description]
     *
     * @return  bool    [return description]
     */
    public function availableShopline(): bool
    {
        return (bool) $this->shopline;
    }

    /**
     * [availablePaypal description]
     *
     * @return  bool    [return description]
     */
    public function availablePaypal(): bool
    {
        return (bool) $this->paypal;
    }

    /**
     * [availableBradesco description]
     *
     * @return  bool    [return description]
     */
    public function availableBradesco(): bool
    {
        return (bool) $this->bradesco;
    }

    /**
     * [getInstallmentValue description]
     *
     * @param   [type]  $totalValue  [$totalValue description]
     *
     * @return  [type]               [return description]
     */
    public function getInstallmentValue(float $totalValue, ?int $maxInstallments = null)
    {
        if (!empty($maxInstallments) && $maxInstallments > 0) {
            return $totalValue / $maxInstallments;
        }

        if ($this->max_installments > 0) {
            return $totalValue / $this->max_installments;
        }

        return null;
    }

    /**
     * [getRemainingInstallments description]
     *
     * @return  [type]  [return description]
     */
    public function getRemainingInstallments(?int $maxInstallments = null)
    {
        if (!$this->firstInstallmentMustBeBillet() && !empty($maxInstallments) && $maxInstallments > 1) {
            return $maxInstallments;
        }

        if ($this->firstInstallmentMustBeBillet()  && !empty($maxInstallments) && $maxInstallments > 1) {
            return $maxInstallments - 1;
        }

        if (!empty($maxInstallments) && $maxInstallments == 0 || $maxInstallments == 1) {
            return 1;
        }

        return 0;
    }

    public function hasInCashDiscount()
    {
        return $this->pivot->discount > 0;
    }

    public function getInCashDiscountedValue($value)
    {
        return $value - $value * ($this->pivot->discount / 100) ?? 1;
    }

    /**
     * [getBookingInstallments description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function getBookingInstallments(Booking $booking)
    {
        $disableFirstInstallmentDiscountByPromocode = 0;
        $installments = [];

        $packagePaymentMethodQuery = $booking->package->paymentMethods()->withPivot([
            'first_installment_billet',
            'first_installment_billet_method_id',
            'first_installment_billet_processor',
            'processor',
            'max_installments',
            'limiter',
            'discount',
            'tax'
        ]);

        $packagePaymentMethod = $packagePaymentMethodQuery->where('payment_method_id', $this->id)->first();

        if (!$packagePaymentMethod) {
            return [];
        }

        $packagePaymentMethod   = $packagePaymentMethod->pivot;
        $firstInstallmentMustBeBillet = (bool) $packagePaymentMethod->first_installment_billet;
        $tax                    = $packagePaymentMethod->tax;
        // Promocode Check
        if($booking->promocode != null){
            $disableFirstInstallmentDiscountByPromocode = $booking->promocode->cancelsCashDiscount();
        }

        for ($i = 0; $i < $packagePaymentMethod->max_installments; $i++) {
            $bookingSubTotal    = $bookingTotal    = (float) decimal($booking->total);
            $taxSubValue        = $taxValue        = 0;
            $installmentsQuantity                  = $packagePaymentMethod->max_installments - $i;

            if($tax>0){
                $taxValue           = $taxSubValue = ($bookingSubTotal * ($tax/100));
                $bookingTotal       += $taxValue;
                $taxValue           = $taxValue / $installmentsQuantity;
            }
            $installmentValue       = $bookingTotal / $installmentsQuantity;

            for ($j = 1; $j <= $installmentsQuantity; $j++) {
                $discountValue      = $discount = 0;
                $valueOnePayment    = $bookingSubTotal;
                
                if ($installmentsQuantity == 1 && $disableFirstInstallmentDiscountByPromocode == 0) {
                    // Rule: when the field "discount" is greater than 0, the system need to calculate
                    // the value only in payments insight.
                    if($packagePaymentMethod->discount >0){
                        $discount = $packagePaymentMethod->discount/100;
                        $discountValue = $discount * $bookingSubTotal;
                    }
                    $valueOnePayment = number_format($bookingSubTotal * (1 - $discount), 2, ".", "");
                    $installments[$i][$j] = [
                        'payment_method_id' => $this->id,
                        'processor'         => $packagePaymentMethod->processor,
                        'type'              => $this->type,
                        'discount'          => $discount,    // Without percent
                        'discount_value'    => $discountValue,
                        'tax'               => $packagePaymentMethod->tax,
                        'taxValue'          => $taxValue,
                        'value'             => (float) $valueOnePayment,
                        'subtotal'          => $bookingSubTotal,
                        'totalTax'          => $taxSubValue,
                        'total'             => ($bookingSubTotal + $taxSubValue) // Total to charge for booking
                    ];
                }else{
                    $installments[$i][$j] = [
                        'payment_method_id' => ($firstInstallmentMustBeBillet && $j && $packagePaymentMethod->first_installment_billet_method_id != null) === 1 ? $packagePaymentMethod->first_installment_billet_method_id : $this->id,
                        'processor'         => ($firstInstallmentMustBeBillet && $j === 1 && $packagePaymentMethod->first_installment_billet_method_id != null) ? $packagePaymentMethod->first_installment_billet_processor : $packagePaymentMethod->processor,
                        'type'              => ($firstInstallmentMustBeBillet && $j === 1 && $packagePaymentMethod->first_installment_billet_method_id != null) ? 'billet' : $this->type,
                        'discount'          => $discount,    // Without percent
                        'discount_value'    => $discountValue,
                        'tax'               => $tax,
                        'taxValue'          => $taxValue,
                        'value'             => (float) number_format($installmentValue, 2, ".",""),
                        'subtotal'          => $bookingSubTotal,
                        'totalTax'          => $taxSubValue,
                        'total'             => $bookingSubTotal + $taxSubValue  // Total to charge for booking
                    ];
                }
            }
        }

        $limiter = $packagePaymentMethod->limiter ?? 0;
        $today = Carbon::now();
        $eventDate = $booking->starts_at->subDays($limiter);

        if ($today >= $eventDate) {
            $maxInstallments = 1;
        } else {
            $maxInstallments = round($today->floatDiffInMonths($eventDate));
        }

        foreach ($installments as $key => $installmentSet) {
            if (count($installmentSet) > $maxInstallments) {
                unset($installments[$key]);
            }
        }

        $installments = array_reverse($installments);

        return $installments;
    }

    /**
     * [getTranslatedLabel description]
     *
     * @param   array  $installments  [$installments description]
     *
     * @return  [type]                [return description]
     */
    public function getTranslatedLabel(Booking $booking, PaymentMethod $paymentMethod, array $installments)
    {
        $total = 0;
        $label = '';

        $packagePaymentMethodQuery = $booking->package->paymentMethods()->withPivot([
            'first_installment_billet',
            'first_installment_billet_method_id',
            'first_installment_billet_processor',
            'processor',
            'max_installments',
            'discount',
            'tax'
        ]);

        $packagePaymentMethod   = $packagePaymentMethodQuery->where('payment_method_id', $paymentMethod->id)->first();
        $packagePaymentMethod   = $packagePaymentMethod->pivot;
        $firstInstallmentMustBeBillet = (bool) $packagePaymentMethod->first_installment_billet;
        $currency                   = $booking->currency;

        $installmentValue           = $installments[1]['value'];
        $discount                   = $installments[1]['discount'];     // Without percent
        $discountValue              = $installments[1]['discount_value'];
        $bookingTotal               = (float) decimal($installments[1]['total']);
        $installmentTax             = $installments[1]['tax'];


        if (count($installments) == 1) {
            if($installmentTax>0){
                $gatewayServiceValue    = ($installmentValue * ($installmentTax/100));
                $installmentValue       = $gatewayServiceValue + $installmentValue;
            }
            $string = money($installmentValue, $currency) . ' '. __("frontend.reservas.a_vista");
            return $string;
        }

        foreach ($installments as $installment) {
            if($installment['tax']>0){
                $gatewayServiceValue = ($installment['value'] * ($installment['tax']/100));
                $installment['value'] = $gatewayServiceValue + $installment['value'];
            }
            
            $total += decimal($installment['value']);
        }

        if($total > $bookingTotal){
            $total = $bookingTotal;
        }

        if (!$firstInstallmentMustBeBillet) {
            return count($installments) . 'x '. __("frontend.geral.de") .' ' . money($installmentValue, $currency) . ' ('. __("frontend.geral.total") .' ' . money($total, $currency) . ')';
        }

        return '1 ('. __("frontend.reservas.titulo_parcelas") .') '. __("frontend.geral.de") .' '. money($installmentValue, $currency) . ' + ' . (count($installments) - 1) . 'x '. __("frontend.geral.de") .' '. money($installmentValue, $currency)  . ' ('. __("frontend.geral.total") .' ' . money($total, $currency)  . ')';
    }
}
