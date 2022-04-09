<?php

namespace App\Models;

use App\Models\Relationships\BelongsToBooking;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingVoucher extends Model
{
    use BelongsToBooking,
        HasDateLabels,
        HasFactory;

    protected $fillable = [
        'booking_id',
        'services',
        'comments',
        'released_at',
    ];

    protected $casts = [
        'released_at' => 'date',
    ];
}
