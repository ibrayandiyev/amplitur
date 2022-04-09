<?php

namespace App\Models;

use App\Enums\OfferType;
use App\Models\Relationships\BelongsToLongtripRoute;
use App\Models\Relationships\MorphManyObservations;
use App\Models\Relationships\MorphOneAddress;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class LongtripBoardingLocation extends Model
{
    use HasFactory,
        BelongsToLongtripRoute,
        MorphManyObservations,
        MorphOneAddress,
        HasDateLabels;

    protected $fillable = [
        'longtrip_route_id',
        'boarding_at',
        'ends_at',
        'country',
        'is_available',
        'price',
    ];

    protected $dates = [
        'boarding_at',
        'ends_at',
    ];

    /**
     * [hasStock description]
     *
     * @param   int   $quantity  [$quantity description]
     *
     * @return  bool             [return description]
     */
    public function hasStock($date, int $quantity = 1): bool
    {
        return $this->longtripRoute->capacity >= $quantity;
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
        $this->longtripRoute->capacity += $quantity;

        return $this->longtripRoute->save();
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
        $this->longtripRoute->capacity -= $quantity;

        return $this->longtripRoute->save();
    }

    /**
     * Get boarding location name with address information
     *
     * @return  string  [return description]
     */
    public function getExtendedNameAttribute(): ?string
    {
        $city = city($this->address->city);
        $country = country($this->country);
        $boardingAtString = mb_strtoupper(__('frontend.pacotes.embarque_em'));

        return "{$this->longtripRoute->label_name} - {$boardingAtString} {$city} - {$country}";
    }

        /**
     * Get boarding location name with address information
     *
     * @return  string  [return description]
     */
    public function getExtendedNameLocation(): ?string
    {
        $city = city($this->address->city);
        $country = country($this->country);
        $boardingAtString = (__('frontend.pacotes.embarque_em'));

        return "{$this->boardingAtString} - {$city} - {$country}";
    }

    /**
     * Get money based on offer currency
     *
     * @return  string
     */
    public function getExtendedPriceAttribute(): ?string
    {
        $currency = $this->longtripRoute->offer->currency;

        return (string) money($this->price, $currency);
    }

    /**
     * Get money based on offer currency, offer sales coefficient
     *
     * @return  string
     */
    public function getExtendedValuePriceAttribute(): ?string
    {
        $currency = $this->longtripRoute->offer->currency;

        return (string) money($this->getPrice(), $currency);
    }

    /**
     * [getTitle description]
     *
     * @return  string  [return description]
     */
    public function getTitle(): ?string
    {
        return $this->getExtendedNameAttribute();
    }

    /**
     * [getPrice description]
     *
     * @return  float   [return description]
     */
    public function getPrice(): ?float
    {
        return $this->price * $this->longtripRoute->offer->saleCoefficient->value;
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
        return ($this->price * $this->longtripRoute->offer->saleCoefficient->value) - $this->price;
    }

    /**
     * [getSaleCoefficient description]
     *
     * @return  float   [return description]
     */
    public function getSaleCoefficient()
    {
        return $this->longtripRoute->offer->saleCoefficient->value;
    }

    /**
     * [getExtendedTitle description]
     *
     * @return  string  [return description]
     */
    public function getExtendedTitle(): ?string
    {
        return $this->getTitle() . " @ {$this->longtripRoute->offer->company->company_name}";
    }

    /**
     * [getOffer description]
     *
     * @return  Offer   [return description]
     */
    public function getOffer(): Offer
    {
        return $this->longtripRoute->offer;
    }

    /**
     * [getGroupType description]
     *
     * @return  string  [return description]
     */
    public function getOfferType(): ?string
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
        return 1;
    }

    /**
     * [getInclusions description]
     *
     * @return  [type]  [return description]
     */
    public function getInclusions()
    {
        return $this->longtripRoute->inclusions;
    }

    /**
     * [getObservations description]
     *
     * @return  [type]  [return description]
     */
    public function getObservations()
    {
        return $this->longtripRoute->observations;
    }

    /**
     * [getFriendlyBoardingDate description]
     *
     * @return  string  [return description]
     */
    public function getFriendlyBoardingDate(): ?string
    {
        return $this->boarding_at->format('d/m/Y - H\hi');
    }

    /**
     * [getFriendlyEndDate description]
     *
     * @return  string  [return description]
     */
    public function getFriendlyEndDate(): ?string
    {
        return $this->ends_at->format('d/m/Y - H\hi');
    }

    /**
     * [getBoardingLocationTitle description]
     *
     * @return  string  [return description]
     */
    public function getBoardingLocationTitle(): ?string
    {
        $address = $this->address->address;
        $number = $this->address->number;
        $neighborhood = $this->address->neighborhood;
        $complement = $this->address->complement;

        return ("{$address}, {$number} {$neighborhood} {$complement}");
    }

    /**
     * [getMapMarkUrl description]
     *
     * @return  string  [return description]
     */
    public function getMapMarkUrl(): ?string
    {
        return 'https://www.amplitur.com.br/imagens/icones/map_ico_saida.png';
    }

    /**
     * [getLatitude description]
     *
     * @return  float   [return description]
     */
    public function getLatitude(): ?float
    {
        return $this->address->latitude;
    }

    /**
     * [getLongitude description]
     *
     * @return  float   [return description]
     */
    public function getLongitude(): ?float
    {
        return $this->address->longitude;
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
     * [getStock description]
     *
     * @return  [type]         [return description]
     */
    public function getStock()
    {
        return $this->longtripRoute->capacity ?? 0;
    }

    /**
     * [setDisableStock description]
     *
     * @return  [type]         [return description]
     */
    public function setDisableStock()
    {
        return $this->longtripRoute->capacity = 0;
    }

    /**
     * [isAvailable description]
     *
     * @return  [type]  [return description]
     */
    public function isAvailable()
    {
        return $this->is_available;
    }

    /**
     * [isOutOfStock description]
     *
     * @return  [type]  [return description]
     */
     public function isOutOfStock()
    {
        return $this->getStock() == 0;
    }

    /**
     * [isRunningOut description]
     *
     * @return  [type]  [return description]
     */
    public function isRunningOut()
    {
        return $this->getStock() <= 4;
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
}
