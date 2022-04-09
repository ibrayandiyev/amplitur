<?php

namespace App\Models;

use App\Enums\OfferType;
use App\Models\Relationships\BelongsToBustripRoute;
use App\Models\Relationships\MorphManyAdditionals;
use App\Models\Relationships\MorphOneAddress;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class BustripBoardingLocation extends Model
{
    use HasFactory,
        BelongsToBustripRoute,
        MorphManyAdditionals,
        MorphOneAddress,
        HasDateLabels;

    protected $fillable = [
        'bustrip_route_id',
        'boarding_at',
        'travel_time',
        'country',
        'price',
        'is_available',
    ];

    protected $dates = [
        'boarding_at',
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
        return $this->bustripRoute->capacity >= $quantity;
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
        $this->bustripRoute->capacity += $quantity;

        return $this->bustripRoute->save();
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
        $this->bustripRoute->capacity -= $quantity;

        return $this->bustripRoute->save();
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
        $state = state($country, $this->address->state);
        $boardingAtString = (__('frontend.pacotes.embarque_em'));

        return "{$boardingAtString} {$city} - {$state} - {$country}";
    }

    /**
     * Get money based on offer currency
     *
     * @return  string
     */
    public function getExtendedPriceAttribute(): ?string
    {
        $currency = $this->bustripRoute->offer->currency;

        return (string) money($this->price, $currency);
    }

    /**
     * Get money based on offer currency, offer sales coefficient
     *
     * @return  string
     */
    public function getExtendedValuePriceAttribute(): ?string
    {
        $currency = $this->bustripRoute->offer->currency;

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
     * [getExtendedTitle description]
     *
     * @return  string  [return description]
     */
    public function getExtendedTitle(): ?string
    {
        return $this->getTitle() . " @ {$this->bustripRoute->offer->company->company_name }";
    }

    /**
     * [getOffer description]
     *
     * @return  Offer   [return description]
     */
    public function getOffer(): Offer
    {
        return $this->bustripRoute->offer;
    }

    /**
     * [getGroupType description]
     *
     * @return  string  [return description]
     */
    public function getOfferType(): ?string
    {
        return OfferType::BUSTRIP;
    }

    /**
     * [getPrice description]
     *
     * @return  float   [return description]
     */
    public function getPrice(): ?float
    {
        return $this->price * $this->bustripRoute->offer->saleCoefficient->value;
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
        return ($this->price * $this->bustripRoute->offer->saleCoefficient->value) - $this->price;
    }

    /**
     * [getSaleCoefficient description]
     *
     * @return  float   [return description]
     */
    public function getSaleCoefficient()
    {
        return $this->bustripRoute->offer->saleCoefficient->value;
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
        return $this->bustripRoute->inclusions;
    }

    /**
     * [getObservations description]
     *
     * @return  [type]  [return description]
     */
    public function getObservations()
    {
        return $this->bustripRoute->observations;
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
     * [getBoardingLocationTitle description]
     *
     * @return  string  [return description]
     */
    public function getBoardingLocationTitle(): ?string
    {
        $address = $this->address->address;
        $number = $this->address->number;
        $city = city($this->address->city);
        $country = country($this->country);
        $state = state($country, $this->address->state);
        $neighborhood = $this->address->neighborhood;
        $complement = $this->address->complement;

        return ("{$address}, {$number} {$neighborhood} {$complement} - {$city} - {$state} - {$country}");
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
        return (float) (($this->address->latitude != null)?$this->address->latitude:0);
    }

    /**
     * [getLongitude description]
     *
     * @return  float   [return description]
     */
    public function getLongitude(): ?float
    {
        return (float) (($this->address->longitude != null)?$this->address->longitude:0);
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
        return $this->bustripRoute->capacity ?? 0;
    }

    /**
     * [setDisableStock description]
     *
     * @return  [type]         [return description]
     */
    public function setDisableStock()
    {
        return $this->bustripRoute->capacity = 0;
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
