<?php

namespace App\Models\Relationships;

use App\Models\BookingVoucherFile;

trait HasManyBookingVoucherFiles
{
    public function bookingVoucherFiles()
    {
        return $this->hasMany(BookingVoucherFile::class);
    }
}

