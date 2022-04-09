<?php

namespace App\Models\Relationships;

use App\Models\PaymentMethod;

trait BelongsToManyPaymentMethods
{
    public function paymentMethods()
    {
        return $this->belongsToMany(PaymentMethod::class);
    }
}