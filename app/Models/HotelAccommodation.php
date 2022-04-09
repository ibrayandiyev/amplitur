<?php

namespace App\Models;

use App\Enums\OfferType;
use App\Models\Relationships\BelongsToHotelAccommodationType;
use App\Models\Relationships\BelongsToHotel;
use App\Models\Relationships\BelongsToHotelOffer;
use App\Models\Relationships\BelongsToManyHotelAccommodationStructures;
use App\Models\Relationships\HasManyHotelAccommodationsPricing;
use App\Models\Relationships\MorphManyAdditionals;
use App\Models\Relationships\MorphManyExclusions;
use App\Models\Relationships\MorphManyInclusions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HotelAccommodation extends Model
{
    use BelongsToHotelOffer,
        BelongsToHotelAccommodationType,
        BelongsToManyHotelAccommodationStructures,
        HasManyHotelAccommodationsPricing,
        MorphManyAdditionals,
        MorphManyInclusions,
        MorphManyExclusions,
        HasTranslations,
        HasFactory;

    protected $fillable = [
        'hotel_offers_id',
        'hotel_accommodation_type_id',
        'images',
        'extra_exclusions',
        'extra_inclusions',
    ];

    protected $casts = [
        'images' => 'array',
    ];

    protected $translatable = [
        'extra_exclusions',
        'extra_inclusions',
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
        return $this->getPricing($date)->stock >= $quantity;
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
        $entity = $this->getPricing($date);
        $entity->stock += $quantity;

        return $entity->save();
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
        $entity = $this->getPricing($date);
        $entity->stock -= $quantity;

        return $entity->save();
    }

    /**
     * [getTypeLabelAttribute description]
     *
     * @return  string  [return description]
     */
    public function getTypeLabelAttribute(): ?string
    {
        return $this->type->name;
    }

    /**
     * [getStructuresLabelAttribute description]
     *
     * @return  string  [return description]
     */
    public function getStructuresLabelAttribute(): ?string
    {
        $string = '';

        foreach ($this->structures as $structure) {
           $string .= '<span class="label label-primary">' . $structure->name . '</span>';
        }

        return $string;
    }

    /**
     * [getTitle description]
     *
     * @return  string  [return description]
     */
    public function getTitle(): ?string
    {
        return $this->getTypeLabelAttribute();
    }

    /**
     * [getExtendedTitle description]
     *
     * @return  string  [return description]
     */
    public function getExtendedTitle(): ?string
    {
        return "{$this->hotelOffer->hotel->name} - " . $this->getTitle() . " @ {$this->hotelOffer->offer->company->company_name}";
    }

    /**
     * [getOffer description]
     *
     * @return  Offer   [return description]
     */
    public function getOffer(): Offer
    {
        return $this->hotelOffer->offer;
    }

    /**
     * [getOfferType description]
     *
     * @return  string  [return description]
     */
    public function getOfferType(): ?string
    {
        return OfferType::HOTEL;
    }

    public function getLowestPrice(): ?string
    {
        $prices = $this->hotelAccommodationsPricings()->where('price', '>', 0)->get();

        $lowestPrice = $prices->min('price');

        return $lowestPrice * $this->hotelOffer->offer->saleCoefficient->value;
    }

    /**
     * [getPrice description]
     *
     * @return  float   [return description]
     */
    public function getPrice($date = null): ?float
    {
        $pricing = $this->getPricing($date);

        return !is_null($pricing) ?  $pricing->price * $this->hotelOffer->offer->saleCoefficient->value : 0;
    }

    /**
     * [getPriceSaleCoefficientValue description]
     *
     * @return  float   [return description]
     */
    public function getPriceSaleCoefficientValue($date = null)
    {
        $pricing = $this->getPricing($date);

        return !is_null($pricing) ?  ($pricing->price * $this->hotelOffer->offer->saleCoefficient->value) - $pricing->price : 0;
    }

    /**
     * [getPriceNet description]
     *
     * @return  float   [return description]
     */
    public function getPriceNet($date=[]): ?float
    {
        $pricing = $this->getPricing($date);

        return !is_null($pricing) ?  $pricing->price : 0;

    }

    /**
     * [getSaleCoefficient description]
     *
     * @return  float   [return description]
     */
    public function getSaleCoefficient()
    {
        return $this->hotelOffer->offer->saleCoefficient->value;
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

    /**
     * [getInclusions description]
     *
     * @return  [type]  [return description]
     */
    public function getInclusions()
    {
        return $this->inclusions;
    }

    /**
     * [getStructures description]
     *
     * @return  [type]  [return description]
     */
    public function getStructures()
    {
        return $this->structures;
    }

    /**
     * [getObservations description]
     *
     * @return  [type]  [return description]
     */
    public function getObservations()
    {
        return new Observation;
    }

    /**
     * [getFriendlyCheckin description]
     *
     * @return  [type]  [return description]
     */
    public function getFriendlyCheckin(): ?string
    {
        return Carbon::createFromFormat('H:i:s', $this->hotelOffer->hotel->checkin)->format('H:i');
    }

    /**
     * [getFriendlyCheckout description]
     *
     * @return  [type]  [return description]
     */
    public function getFriendlyCheckout(): ?string
    {
        return Carbon::createFromFormat('H:i:s', $this->hotelOffer->hotel->checkout)->format('H:i');
    }

    /**
     * [getHotelName description]
     *
     * @return  string  [return description]
     */
    public function getHotelName(): ?string
    {
        return $this->hotelOffer->hotel->name;
    }

    /**
     * [getMapMarkUrl description]
     *
     * @return  string  [return description]
     */
    public function getMapMarkUrl(): ?string
    {
        return 'https://www.amplitur.com.br/imagens/icones/map_ico_hotel.png';
    }

    /**
     * [getLatitude description]
     *
     * @return  mixed   [return description]
     */
    public function getLatitude()
    {
        return $this->hotelOffer->hotel->address->latitude;
    }

    /**
     * [getLongitude description]
     *
     * @return  mixed   [return description]
     */
    public function getLongitude()
    {
        return $this->hotelOffer->hotel->address->longitude;
    }

    /**
     * [hasImages description]
     *
     * @return  bool    [return description]
     */
    public function hasImages(): bool
    {
        return !empty($this->images);
    }

    /**
     * [getStockStatusClass description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function getStockStatusClass($date)
    {
        if ($this->isOutOfStock($date)) {
            return 'esgotado';
        }

        if ($this->isRunningOut($date)) {
            return 'esgotando';
        };

        return 'disponivel';
    }

    /**
     * [getStock description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function getStock($date)
    {
        $pricing = $this->getPricing($date);

        return $pricing->stock ?? 0;
    }

    /**
     * [isOutOfStock description]
     *
     * @return  [type]  [return description]
     */
    public function isOutOfStock($date)
    {
        return $this->getStock($date) == 0;
    }

    /**
     * [isRunningOut description]
     *
     * @return  [type]  [return description]
     */
    public function isRunningOut($date)
    {
        return $this->getStock($date) <= 4;
    }

    /**
     * [isOneAvailable description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function isOneAvailable($date)
    {
        return $this->getStock($date) == 1;
    }

    /**
     * [getPricing description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function getPricing($date)
    {
        $pricing = $this->hotelAccommodationsPricings()->where('checkin', $date)->first();

        return $pricing;
    }

    /**
     * [getStockLabel description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function getStockLabel($date)
    {
        if ($this->isOutOfStock($date)) {
            return (__('frontend.reservas.esgotado'));
        }

        if ($this->isOneAvailable($date)) {
            return (__('frontend.reservas.ultimas_unit'));
        }

        if ($this->isRunningOut($date)) {
            return "{$this->getStock($date)} ÚLTIMAS UNIDADES";
        }
    }

    /**
     * [Hotel Address description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    public function gethoteladdress()
    {
        $address = $this->hotelOffer->hotel->address->address;
        $number = $this->hotelOffer->hotel->address->number;
        $city = city($this->hotelOffer->hotel->address->city);
        $country = country($this->hotelOffer->hotel->address->country);
        $state = state($country, $this->hotelOffer->hotel->address->state);
        $neighborhood = $this->hotelOffer->hotel->address->neighborhood;
        $complement = $this->hotelOffer->hotel->address->complement;

        return ("{$address}, {$number} {$neighborhood} {$complement} - {$city} - {$state} - {$country}");

    }
}
