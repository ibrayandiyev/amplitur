<?php

namespace App\Models;

use App\Models\Relationships\BelongsToBooking;
use App\Models\Relationships\BelongsToBookingBill;
use App\Models\Relationships\BelongsToPaymentMethod;
use App\Models\Relationships\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodTransaction extends Model
{
    use BelongsToPaymentMethod,
        BelongsToBooking,
        BelongsToBookingBill,
        BelongsToUser,
        HasFactory;

    protected $fillable = [
        'payment_method_id',
        'booking_id',
        'booking_bill_id',
        'user_id',
        'value',
        'installment',
        'country',
        'gateway',
        'gateway_response_code',
        'gateway_response_message',
        'gateway_payload',
        'order',
        'authorization',
        'nsu',
        'authentication',
        'sqn',
        'rid',
        'holder',
    ];
}
