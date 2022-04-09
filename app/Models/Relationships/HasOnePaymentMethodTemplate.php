<?php

namespace App\Models\Relationships;

use App\Models\PaymentMethodTemplate;

trait HasOnePaymentMethodTemplate
{
    public function paymentMethodTemplate()
    {
        return $this->hasOne(PaymentMethodTemplate::class);
    }
}
