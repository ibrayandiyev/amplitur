<?php

namespace App\Models;

use App\Models\Relationships\BelongsToBooking;
use App\Models\Relationships\BelongsToCurrency;
use App\Models\Relationships\BelongsToCurrencyOrigin;
use App\Models\Relationships\BelongsToOffer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingOffer extends Model
{
    use BelongsToBooking,
        BelongsToOffer,
        BelongsToCurrency,
        BelongsToCurrencyOrigin,
        HasFactory;

    protected $fillable = [
        'booking_id',
        'offer_id',
        'currency_id',
        'currency_origin_id',
        'company_id',
        'sale_coefficient',
        'price',
        'price_net'
    ];
}
