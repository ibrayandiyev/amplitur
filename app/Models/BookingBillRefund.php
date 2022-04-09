<?php

namespace App\Models;

use App\Models\Relationships\BelongsToBooking;
use App\Models\Relationships\BelongsToBookingBill;
use App\Models\Relationships\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingBillRefund extends Model
{
    use BelongsToBooking,
        BelongsToBookingBill,
        BelongsToUser,
        HasFactory;

    protected $fillable = [
        'booking_id',
        'booking_bill_id',
        'user_id',
        'value',
        'refunded_at',
        'status',
        'history', 
        'json_object'
    ];
}
