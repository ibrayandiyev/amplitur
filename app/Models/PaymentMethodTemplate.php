<?php

namespace App\Models;

use App\Models\Relationships\BelongsToPaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodTemplate extends Model
{
    use BelongsToPaymentMethod,
        HasFactory;

    protected $fillable = [
        'payment_method_id',
        'processor',
        'tax',
        'discount',
        'limiter',
        'max_installments',
        'first_installment_billet',
        'first_installment_billet_method_id',
        'first_installment_billet_processor',
        'is_active',
    ];
}
