<?php

namespace App\Models;

use App\Enums\OfferType;
use App\Models\Relationships\BelongsToLongtripAccommodationType;
use App\Models\Relationships\BelongsToLongtripRoute;
use App\Models\Relationships\BelongsToOffer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LongtripAccommodationsPricing extends Model
{
    use BelongsToOffer,
        BelongsToLongtripRoute,
        BelongsToLongtripAccommodationType,
        HasFactory;

    protected $fillable = [
        'offer_id',
        'longtrip_route_id',
        'longtrip_accommodation_type_id',
        'price',
        'stock',
    ];

    /**
     * [getTitle description]
     *
     * @return  string  [return description]
     */
    public function getTitle(): ?string
    {
        return $this->type->name;
    }

    /**
     * [getTitle description]
     *
     * @return  string  [return description]
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * [getExtendedTitle description]
     *
     * @return  string  [return description]
     */
    public function getExtendedTitle(): ?string
    {
        return  "{$this->longtripRoute->label_name} - {$this->type->name} @ {$this->offer->company->company_name}" ;
    }

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
     * [getPrice description]
     *
     * @return  [type]  [return description]
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * Get money based on offer currency, offer sales coefficient
     *
     * @return  string
     */
    public function getExtendedValuePriceAttribute(): ?string
    {
        $currency = $this->offer->currency;

        return (string) money($this->getPrice(), $currency);
    }

    /**
     * [getPriceNet description]
     *
     * @return  float   [return description]
     */
    public function getPriceNet(): ?float
    {
        return $this->price;
    }

    /**
     * [getPriceSaleCoefficientValue description]
     *
     * @return  float   [return description]
     */
    public function getPriceSaleCoefficientValue()
    {
        return ($this->price * $this->offer->saleCoefficient->value) - $this->price;
    }

    /**
     * [getSaleCoefficient description]
     *
     * @return  float   [return description]
     */
    public function getSaleCoefficient()
    {
        return $this->offer->saleCoefficient->value;
    }

    /**
     * [getStockStatusClass description]
     *
     * @return  [type]         [return description]
     */
    public function getStockStatusClass()
    {
        if ($this->isOutOfStock()) {
            return 'esgotado';
        }

        if ($this->isRunningOut()) {
            return 'esgotando';
        };

        return 'disponivel';
    }

    /**
     * Check if additionals is in stock
     *
     * @return  bool
     */
    public function hasStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * [putStock description]
     *
     * @param   [type]$date      [$date description]
     * @param   int  $quantity  [$quantity description]
     *
     * @return  [type]          [return description]
     */
    public function putStock($date, int $quantity = 1)
    {
        $this->stock += $quantity;

        return $this->save();
    }

    /**
     * [pickStock description]
     *
     * @param   [type]$date      [$date description]
     * @param   int  $quantity  [$quantity description]
     *
     * @return  [type]          [return description]
     */
    public function pickStock($date, int $quantity = 1)
    {
        $this->stock -= $quantity;

        return $this->save();
    }

    /**
     * [isOutOfStock description]
     *
     * @return  bool    [return description]
     */
    public function isOutOfStock(): bool
    {
        return !$this->hasStock();
    }

    /**
     * [isRunningOut description]
     *
     * @return  bool    [return description]
     */
    public function isRunningOut(): bool
    {
        return $this->stock <= 4;
    }

    /**
     * [isOneAvailable description]
     *
     * @return  [type]         [return description]
     */
    public function isOneAvailable()
    {
        return $this->getStock() == 1;
    }

    /**
     * [getStockLabel description]
     *
     * @return  [type]         [return description]
     */
    public function getStockLabel()
    {
        if ($this->isOutOfStock()) {
            return (__('frontend.reservas.esgotado'));
        }

        if ($this->isOneAvailable()) {
            return (__('frontend.reservas.ultimas_unit'));
        }

        if ($this->isRunningOut()) {
            return "{$this->getStock()} ÚLTIMAS UNIDADES";
        }
    }

    /**
     * Get current stock
     *
     * @return  bool
     */
    public function getStock(): int
    {
        return $this->stock ?? 0;
    }

    /**
     * [getOfferType description]
     *
     * @return  [type]  [return description]
     */
    public function getOfferType()
    {
        return OfferType::LONGTRIP;
    }

    /**
     * [getCapacity description]
     *
     * @return  int     [return description]
     */
    public function getCapacity(): int
    {
        return $this->type->capacity;
    }
}
