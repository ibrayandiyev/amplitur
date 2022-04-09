<?php

namespace App\Models;

use App\Models\Relationships\BelongsToHotelAccommodation;
use App\Models\Relationships\BelongsToOffer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelAccommodationsPricing extends Model
{
    use BelongsToOffer,
        BelongsToHotelAccommodation,
        HasFactory;

    protected $fillable = [
        'offer_id',
        'hotel_accommodation_id',
        'checkin',
        'checkout',
        'price',
        'stock',
        'required_overnight',
    ];

    protected $casts = [
        'checkin' => 'date',
        'checkout' => 'date',
        'required_overnight' => 'bool',
    ];

    /**
     * [getReceiveablePriceAttribute description]
     *
     * @return  string  [return description]
     */
    public function getReceiveablePriceAttribute(): ?string
    {
        $salePrice = $this->price * $this->offer->saleCoefficient->value;

        return money($salePrice, $this->offer->currency, $this->offer->currency);
    }

    /**
     * [getPriceNet description]
     *
     * @return  string  [return description]
     */
    public function getPriceNet(): ?string
    {
        $salePrice = $this->price * $this->offer->saleCoefficient->value;

        return $salePrice;
    }

    /**
     * [getCheckinDateString description]
     *
     * @return  string  [return description]
     */
    public function getCheckinDateString(): ?string
    {
        return $this->checkin->format('d/m/Y');
    }

    /**
     * [isRequired description]
     *
     * @return  bool    [return description]
     */
    public function isRequired(): bool
    {
        $bookablePeriod = $this->offer->package->bookablePeriod;

        foreach ($bookablePeriod as $key => $period) {
            if ($period['date'] == $this->checkin && $period['required']) {
                return true;
            }
        }

        return false;
    }
}