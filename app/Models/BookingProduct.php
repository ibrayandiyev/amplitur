<?php

namespace App\Models;

use App\Models\Relationships\BelongsToBooking;
use App\Models\Relationships\BelongsToCurrency;
use App\Models\Relationships\BelongsToCurrencyOrigin;
use App\Models\Traits\ProductPrices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingProduct extends Model
{
    use BelongsToCurrency,
        BelongsToCurrencyOrigin,
        BelongsToBooking,
        ProductPrices,
        HasFactory;

    protected $fillable = [
        'booking_id',
        'currency_id',
        'currency_origin_id',
        'company_id',
        'product_type',
        'product_id',
        'date',
        'sale_coefficient',
        'price',
        'price_net'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * [getProduct description]
     *
     * @return  [type]  [return description]
     */
    public function getProduct()
    {
        $instance = app($this->product_type);
        $product = $instance->find($this->product_id);

        switch(get_class($instance)){
            case LongtripAccommodationsPricing::class:
                if($product == null){
                    // Could be a LongtripBoardinLocation
                    $instance = app(LongtripBoardingLocation::class);
                    $this->product_type = LongtripBoardingLocation::class;
                    $product = $instance->find($this->product_id);
                }
                break;
            default:
                break;
        }

        return $product;
    }

    /**
     * [getTitle description]
     *
     * @return  [type]  [return description]
     */
    public function getTitle()
    {
        return $this->getProduct()->getTitle();
    }

    /**
     * [getProductPrice description]
     *
     * @return  [type]  [return description]
     */
    public function getProductPrice()
    {
        return $this->getProduct()->getPrice();
    }

    /**
     * [getProductPriceNet description]
     *
     * @return  float   [return description]
     */
    public function getProductPriceNet()
    {
        return $this->getProduct()->getPriceNet();
    }

    
}
