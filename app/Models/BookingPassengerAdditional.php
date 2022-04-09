<?php

namespace App\Models;

use App\Models\Relationships\BelongsToAdditional;
use App\Models\Relationships\BelongsToBooking;
use App\Models\Relationships\BelongsToBookingPassenger;
use App\Models\Relationships\BelongsToCurrencyOrigin;
use App\Models\Relationships\BelongsToHotelAccommodationType;
use App\Models\Traits\ProductPrices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPassengerAdditional extends Model
{
    use BelongsToBooking,
        BelongsToBookingPassenger,
        BelongsToHotelAccommodationType,
        BelongsToAdditional,
        BelongsToCurrencyOrigin,
        ProductPrices,
        HasFactory;

    protected $fillable = [
        'booking_id',
        'booking_passenger_id',
        'additional_id',
        'currency_id',
        'currency_origin_id',
        'company_id',
        'sale_coefficient',
        'price',
        'price_net'
    ];

    /**
     * [putStock description]
     *
     * @param   BookingPassengerAdditional  $bookingPassengerAdditional  [$bookingPassengerAdditional description]
     * @param   int                         $quantity                    [$quantity description]
     *
     * @return  [type]                                                   [return description]
     */
    public function putStock(BookingPassengerAdditional $bookingPassengerAdditional, int $quantity = 1)
    {
        return $bookingPassengerAdditional->additional->putStock($quantity);
    }

    /**
     * [pickStock description]
     *
     * @param   BookingPassengerAdditional  $bookingPassengerAdditional  [$bookingPassengerAdditional description]
     * @param   int                         $quantity                    [$quantity description]
     *
     * @return  [type]                                                   [return description]
     */
    public function pickStock(BookingPassengerAdditional $bookingPassengerAdditional, int $quantity = 1)
    {
        return $bookingPassengerAdditional->additional->pickStock($quantity);
    }

    /**
     * [hasStock description]
     *
     * @param   BookingPassengerAdditional  $bookingPassengerAdditional  [$bookingPassengerAdditional description]
     * @param   int                         $quantity                    [$quantity description]
     *
     * @return  [type]                                                   [return description]
     */
    public function hasStock(BookingPassengerAdditional $bookingPassengerAdditional, int $quantity = 1)
    {
        return $bookingPassengerAdditional->additional->hasStock($quantity);
    }
}
