<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Enums\OfferType;
use App\Enums\ProcessStatus;
use App\Models\Relationships\BelongsToCompany;
use App\Models\Relationships\BelongsToPackage;
use App\Models\Relationships\BelongsToProvider;
use App\Models\Relationships\BelongsToSaleCoefficient;
use App\Models\Relationships\HasManyAdditionalGroups;
use App\Models\Relationships\HasManyAdditionals;
use App\Models\Relationships\HasManyBookings;
use App\Models\Relationships\HasManyBustripRoutes;
use App\Models\Relationships\HasManyHotelAccommodationsPricing;
use App\Models\Relationships\HasManyImages;
use App\Models\Relationships\HasManyLongtripAccommodations;
use App\Models\Relationships\HasManyLongtripAccommodationsPricing;
use App\Models\Relationships\HasManyLongtripRoutes;
use App\Models\Relationships\HasManyShuttleRoutes;
use App\Models\Relationships\HasOneHotelOffer;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasFlags;
use App\Models\Traits\HasOfferTypeLabels;
use App\Models\Traits\HasProcessStatusLabels;
use App\Repositories\CurrencyRepository;
use App\Repositories\OfferRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\Currency;
use App\Models\Relationships\HasManyBookingPassengerAdditionals;
use DateInterval;
use DatePeriod;
use DateTime;

class Offer extends BaseModel
{
    use BelongsToProvider,
        BelongsToCompany,
        BelongsToPackage,
        BelongsToSaleCoefficient,
        HasDateLabels,
        HasOneHotelOffer,
        HasProcessStatusLabels,
        HasManyBustripRoutes,
        HasManyLongtripRoutes,
        HasManyLongtripAccommodations,
        HasManyLongtripAccommodationsPricing,
        HasManyShuttleRoutes,
        HasManyAdditionals,
        HasManyAdditionalGroups,
        HasManyHotelAccommodationsPricing,
        HasManyBookings,
        HasManyBookingPassengerAdditionals,
        HasFlags,
        HasOfferTypeLabels,
        HasManyImages,
        HasFactory;

    protected $fillable = [
        'provider_id',
        'company_id',
        'package_id',
        'sale_coefficient_id',
        'type',
        'expires_at',
        'ip',
        'currency',
        'status',
        'can_register_additionals',
        'image',
        'flags',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'expires_at' => 'datetime',
        'flags' => 'array',
    ];

    protected $additionalTypes = [
        OfferType::TICKET,
        OfferType::TRAVEL_INSURANCE,
        OfferType::FOOD,
        OfferType::AIRFARE,
        OfferType::TRANSFER,
        OfferType::ADDITIONAL,
    ];

    /**
     * @var array
     */
    public $lowestPrice = null;

    public function getExtendedNameAttribute(): ?string
    {
        if ($this->isHotel()) {
            return $this->hotelOffer->hotel->name;
        }

        return null;
    }

    /**
     * Check if provider can register additionals for this offer
     *
     * @return  bool
     */
    public function canRegisterAdditionals(): bool
    {
        return $this->can_register_additionals == 1;
    }

    /**
     * [getAdditionalsAttribute description]
     *
     * @return  Collection[return description]
     */
    public function getAdditionalsAttribute(): Collection
    {
        return app(OfferRepository::class)->getAdditionals($this);
    }

    /**
     * [getProducts description]
     *
     * @return  Collection[return description]
     */
    public function getProducts(): Collection
    {
        $products = app(OfferRepository::class)->getProducts($this);

        return $products;
    }

    public function getUnpricedProducts(?string $type = null): Collection
    {
        $products = app(OfferRepository::class)->getUnpricedProducts($this, $type);

        return $products;
    }

    /**
     * [getLowestPrice description]
     *
     * @return  string   [return description]
     */
    public function getLowestPrice(): ?string
    {
        if (is_null($this->lowestPrice)) {
            $this->lowestPrice = app(\App\Services\LowestOfferPriceService::class)->getPackageLowestPrice($this->package, $this);
        }

        return $this->lowestPrice['price'];
    }

    /**
     * [getCurrency description]
     *
     * @return  string   [return description]
     */
    public function getCurrency(): ?Currency 
    {
        $currency = app(CurrencyRepository::class)->findByCode($this->currency);

        return $currency;
    }

    /**
     * [getLowestPriceCurrency description]
     *
     * @return  string   [return description]
     */
    public function getLowestPriceCurrency(): ?string
    {
        if (is_null($this->lowestPrice)) {
            $this->lowestPrice = app(\App\Services\LowestOfferPriceService::class)->getPackageLowestPrice($this->package, $this);
        }

        return $this->lowestPrice['currency'];
    }

    /**
     * [isHotel description]
     *
     * @return  bool    [return description]
     */
    public function isHotel(): bool
    {
        return $this->type == OfferType::HOTEL;
    }

    /**
     * [isBustrip description]
     *
     * @return  bool    [return description]
     */
    public function isBustrip(): bool
    {
        return $this->type == OfferType::BUSTRIP;
    }

    /**
     * [isLongtrip description]
     *
     * @return  bool    [return description]
     */
    public function isLongtrip(): bool
    {
        return $this->type == OfferType::LONGTRIP;
    }

    /**
     * [isShuttle description]
     *
     * @return  bool    [return description]
     */
    public function isShuttle(): bool
    {
        return $this->type == OfferType::SHUTTLE;
    }

    /**
     * [isAdditional description]
     *
     * @return  bool    [return description]
     */
    public function isAdditional(): bool
    {
        return in_array($this->type, $this->additionalTypes);
    }

    /**
     * [getGallery description]
     *
     * @return  [type]  [return description]
     */
    public function getGallery()
    {
        return $this->images()->get();
    }

    /**
     * [getAdditionalImage description]
     *
     * @return  [type]  [return description]
     */
    public function getAdditionalImage()
    {
        if (!$this->isAdditional()) {
            return null;
        };

        return $this->images()->where('is_default', true)->first();
    }

    /**
     * [setStatus description]
     *
     * @return  bool    [return description]
     */
    public function setStatus($status = ProcessStatus::PENDING): string
    {
        return $this->status = $status;
    }

    /**
     * [hasBookings description]
     *
     * @return  bool    [return description]
     */
    public function hasBookings(): bool
    {
        return $this->bookings()->count() > 0;
    }

    /**
     * [hasBookingAdditionals description]
     *
     * @return  bool    [return description]
     */
    public function hasBookingAdditionals(): bool
    {
        $_additionals[] = -1;
        foreach($this->additionalGroups as $additionalGroup){
            if($additionalGroup->additionals){
                $_additionals = array_merge($_additionals, $additionalGroup->additionals->pluck("id")->toArray());
            }
        }
        $bookingPassengerAdditionals = app(BookingPassengerAdditional::class)->whereIn("additional_id", $_additionals)->get();

        return $bookingPassengerAdditionals->count() > 0;
    }

    
    /**
     * [hasBookings description]
     *
     * @return  bool    [return description]
     */
    public function calculateSellPrice($price=0): bool
    {
        $saleCoefficient = $this->saleCoefficient()->value;
        return $price + ($price * $saleCoefficient);
    }

    /**
     * [getBookablePeriodAttribute description]
     *
     * @return  [type]  [return description]
     */
    public function getPackageBookablePeriodAttribute()
    {
        $this->package->offerType  = $this->type;

        $bookablePeriods = $this->package->bookablePeriod;
        return $bookablePeriods;
    }
}
