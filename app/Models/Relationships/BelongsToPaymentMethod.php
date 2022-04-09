<?php

namespace App\Models\Relationships;

use App\Models\PaymentMethod;

trait BelongsToPaymentMethod
{
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}