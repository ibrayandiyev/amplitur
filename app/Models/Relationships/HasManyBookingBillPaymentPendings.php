<?php

namespace App\Models\Relationships;

use App\Enums\ProcessStatus;
use App\Models\BookingBill;

trait HasManyBookingBillPaymentPendings 
{
    public function bookingBillPaymentPendings()
    {
        return $this->hasMany(BookingBill::class)->where("status", "=", ProcessStatus::PENDING);
    }
}
