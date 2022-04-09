<?php

namespace App\Models;

use App\Enums\AccessStatus;
use App\Enums\DisplayType;
use App\Enums\OfferStatus;
use App\Enums\OfferType;
use App\Enums\ProcessStatus;
use App\Models\Relationships\BelongsToEvent;
use App\Models\Relationships\BelongsToManyPaymentMethods;
use App\Models\Relationships\BelongsToProvider;
use App\Models\Relationships\HasManyOffers;
use App\Models\Relationships\HasManyAdditionals;
use App\Models\Relationships\HasManyAdditionalGroups;
use App\Models\Relationships\HasManyBookings;
use App\Models\Relationships\MorphOneAddress;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasFlags;
use App\Models\Traits\HasProcessStatusLabels;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Translatable\HasTranslations;
use Str;

class Package extends Model
{
    use BelongsToEvent,
        BelongsToProvider,
        BelongsToManyPaymentMethods,
        MorphOneAddress,
        HasManyOffers,
        HasManyAdditionals,
        HasManyAdditionalGroups,
        HasManyBookings,
        HasProcessStatusLabels,
        HasDateLabels,
        HasFlags,
        HasTranslations,
        HasFactory;

    protected $fillable = [
        'event_id',
        'provider_id',
        'name',
        'country',
        'starts_at',
        'ends_at',
        'location',
        'website',
        'status',
        'flags',
        'token',
        'display_type',
        'description',
        'meta_keywords',
        'meta_description',
        'payment_expire_days',
        'visit',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'flags' => 'array',
    ];

    protected $with = [
        'address',
    ];

    protected $appends = [
        'extendedName',
    ];

    public $translatable = [
        'description',
        'meta_keywords',
        'meta_description',
    ];

    /**
     * @var array
     */
    public $lowestPrice = null;

    /**
     * @var OfferType
     */
    public $offerType = null;

    /**
     * Get name extended with city and country
     *
     * @return  string
     */
    public function getExtendedNameAttribute(): ?string
    {
        $city = city($this->address->city);
        $country = country($this->country);
        $date = $this->starts_at->format('d/m/Y H:i');

        if ($this->event->hasRangeDatesDuration()) {
            $date .= ' ~ ' . $this->ends_at->format('d/m/Y H:i');
        }

        return "{$this->getTitle()} - {$city} - {$country} ({$date})";
    }

    /**
     * Get name extended with city and country
     *
     * @return  string
     */
    public function getExtendedNameDateAttribute(): ?string
    {
        $city = city($this->address->city);
        $country = country($this->country);
        $date = $this->starts_at->format('d/m/Y');

        if ($this->event->hasRangeDatesDuration()) {
            $date .= ' ~ ' . $this->ends_at->format('d/m/Y');
        }

        return "{$this->event->name} - {$city} - {$country} - {$date}";
    }

    public function getDateAttribute(): ?string
    {
        $date = $this->starts_at->format('d/m/Y'); 

        if ($this->event->hasRangeDatesDuration()) {
            $date .= ' ~ ' . $this->ends_at->format('d/m/Y');
        }

        return $date;
    }

    /**
     * [getExtendedTitle description]
     *
     * @return  string  [return description]
     */
    public function getExtendedTitle(): ?string
    {
        $city = city($this->address->city);
        $country = country($this->country);

        return "{$this->event->name} - {$city} - {$country}";
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
     * Check if it's a public package
     *
     * @return  bool
     */
    public function isPublic(): bool
    {
        return $this->display_type == DisplayType::PUBLIC;
    }

    /**
     * Check if it's a non-listed package
     *
     * @return  bool
     */
    public function isNonListed(): bool
    {
        return $this->display_type == DisplayType::NON_LISTED;
    }

    /**
     * Check if it's a non-listed package
     *
     * @return  bool
     */
    public function setDisplayType($displayType)
    {
        $this->display_type = $displayType;
    }

    /**
     * [getPeriodAttribute description]
     *
     * @return  array   [return description]
     */
    public function getPeriodAttribute()
    {
        if (empty($this->ends_at)) {
            $startsAt = $this->starts_at->setTime(0, 0, 0);
            return [$startsAt];
        }

        $dates = [];

        $period = new DatePeriod(
            new DateTime($this->starts_at->format('Y-m-d')),
            new DateInterval('P1D'),
            new DateTime($this->ends_at->addDay()->format('Y-m-d'))
        );

        foreach ($period as $date) {
            $dates[] = $date->setTime(0, 0, 0);
        }

        return $dates;
    }

    /**
     * [getBookablePeriodAttribute description]
     *
     * @return  [type]  [return description]
     */
    public function getBookablePeriodAttribute()
    {
        if (empty($this->ends_at)) {
            $startsAt = $this->starts_at->addDays(-2);
            $endsAt = $this->starts_at->addDays(2);
        } else {
            $startsAt = $this->starts_at->addDays(-2);
            $endsAt = $this->ends_at->addDays(3);
        }

        /**
         * Rule: Ticket and Food offers can provide bookable date only in the package event day
         */
        switch($this->offerType){
            case OfferType::TICKET:
            case OfferType::FOOD:
                if (empty($this->ends_at)) {
                    $endsAt = $startsAt = $this->starts_at;
                } else {
                    $startsAt = $this->starts_at;
                    $endsAt = $this->ends_at->endOfDay();
                }
                break;
        }

        $dates = [];

        $bookablePeriod = new DatePeriod(
            new DateTime($startsAt),
            new DateInterval('P1D'),
            new DateTime($endsAt)
        );

        foreach ($bookablePeriod as $key => $date) {
            $date = $date->setTime(0, 0, 0);
            $dates[$key]['date'] = $date;
            $dates[$key]['required'] = $date >= $this->period[0] && $date <= $this->period[sizeof($this->period) - 1];
        }

        return $dates;
    }


    /**
     * [geTitle description]
     *
     * @return  string  [return description]
     */
    public function getTitle(): ?string
    {
        return $this->name;
    }

    /**
     * [getDescription description]
     *
     * @return  string  [return description]
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * [getUrl description]
     *
     * @return  string  [return description]
     */
    public function getUrl(): ?string
    {
        return route(language().'.frontend.packages.show', [$this->id, $this->event->slug]);
    }

    /**
     * [getSlug description]
     *
     * @return  [type]  [return description]
     */
    public function getSlug()
    {
        return $this->event->slug;
    }

    /**
     * [getThumbnailUrl description]
     *
     * @return  string  [return description]
     */
    public function getThumbnailUrl(): ?string
    {
        $image = $this->event->images()
            ->where('package_id', $this->id)
            ->first();

        if (empty($image)) {
            $image = $this->event->images()
                ->where('is_default', true)
                ->first();
        }

        if (empty($image)) {
            return url('frontend/images/img_nao_disponivel.png');
        }

        return $image->getThumbnailUrl();
    }

    /**
     * [getThumbnail2xUrl description]
     *
     * @return  string  [return description]
     */
    public function getThumbnail2xUrl(): ?string
    {
        $image = $this->event->images()
            ->where('package_id', $this->id)
            ->first();

        if (empty($image)) {
            $image = $this->event->images()
                ->where('is_default', true)
                ->first();
        }

        if (empty($image)) {
            return url('frontend/images/img_nao_disponivel.png');
        }

        return $image->getThumbnail2xUrl();
    }


    /**
     * [getThumbnailAlt description]
     *
     * @return  string  [return description]
     */
    public function getThumbnailAlt(): ?string
    {
        return __('frontend.packages.seo.cover', ['name' => $this->event->name]);
    }

    /**
     * [getLowerPriceCurrency description]
     *
     * @return  string  [return description]
     */
    public function getLowerPriceCurrency(): ?string
    {
        if (is_null($this->lowestPrice)) {
            $this->lowestPrice = app(\App\Services\LowestOfferPriceService::class)->getPackageLowestPrice($this, null,
                ['statusOffer' => OfferStatus::ACTIVE]
            );
        }

        return $this->lowestPrice['currency'];
    }

    /**
     * [getLowerPrice description]
     *
     * @return  float  [return description]
     */
    public function getLowerPrice(): ?float
    {
        if (is_null($this->lowestPrice)) {
            $this->lowestPrice = app(\App\Services\LowestOfferPriceService::class)->getPackageLowestPrice($this, null,
            ['statusOffer' => OfferStatus::ACTIVE]
            );
        }

        return $this->lowestPrice['price'];
    }

    /**
     * [getLowerPriceFloat description]
     *
     * @return  string  [return description]
     */
    public function getLowerPriceFloat(): ?string
    {
        if (is_null($this->lowestPrice)) {
            $this->lowestPrice = app(\App\Services\LowestOfferPriceService::class)->getPackageLowestPrice($this, null,
            ['statusOffer' => OfferStatus::ACTIVE]
            );
        }

        return money($this->lowestPrice['price']);
    }

    /**
     * [getAvailability description]
     *
     * @return  string  [return description]
     */
    public function getAvailability(): ?string
    {
        return "InStock";
    }

    /**
     * [getDateString description]
     *
     * @return  string  [return description]
     */
    public function getDateString(): ?string
    {
        $period = $this->period;

        if (count($period) > 1) {
            return $period[0]->format('d') . ' – ' . $period[sizeof($period) - 1]->format('d M');
        }

        return $period[0]->format('d M');
    }

    public function getDateStringSEO(): ?string
    {
        $period = $this->period;

        if (count($period) > 1) {
            return $period[0]->format('d/m/Y') . ' – ' . $period[sizeof($period) - 1]->format('d/m/Y');
        }

        return $period[0]->format('d/m/Y');
    }

    /**
     * [getFriendlyDate description]
     *
     * @return  string  [return description]
     */
    public function getFriendlyDate(): ?string
    {
        $date = $this->starts_at->format('d/m/Y - H\hi');

        if ($this->event->hasRangeDatesDuration()) {
            $date .= " ". __('frontend.geral.a') ." " . $this->ends_at->format('d/m/Y - H\hi');
        }

        return $date;
    }

    /**
     * [getFriendlyStartDate description]
     *
     * @return  string  [return description]
     */
    public function getFriendlyStartDate(): ?string
    {
        $period = $this->period;

        return $period[0]->format('d/m/Y');
    }

    /**
     * [getFriendlyStartDate description]
     *
     * @return  string  [return description]
     */
    public function getFriendlyEndDate(): ?string
    {
        $period = $this->period;

        return $period[sizeof($period) - 1]->format('d/m/Y');
    }

    /**
     * [getStartDate description]
     *
     * @return  string  [return description]
     */
    public function getStartDate(): ?string
    {
        $period = $this->period;

        return $period[0]->format('y-m-d');
    }

    /**
     * [getEndDate description]
     *
     * @return  string  [return description]
     */
    public function getEndDate(): ?string
    {
        $period = $this->period;

        return $period[sizeof($period) - 1]->format('y-m-d');
    }

    /**
     * [getLocation description]
     *
     * @return  string  [return description]
     */
    public function getLocation(): ?string
    {
        return ($this->location);
    }

    /**
     * [getCity description]
     *
     * @return  string  [return description]
     */
    public function getCity(): ?string
    {
        return city($this->address->city);
    }

    /**
     * [getCountry description]
     *
     * @return  string  [return description]
     */
    public function getCountry(): ?string
    {
        return country($this->address->country);
    }

    /**
     * [getPrebookingUrl description]
     *
     * @return  string  [return description]
     */
    public function getPrebookingUrl(): ?string
    {
        return route('frontend.prebookings.create', [$this->id, $this->event->slug]);
    }

    /**
     * [getOffersProducts description]
     *
     * @return  Collection   [return description]
     */
    public function getOffersProducts(): Collection
    {
        $products = collect();

        foreach ($this->offers as $offer) {
            $products->push($offer->getProducts());
        }

        return $products->flatten(1);
    }

    /**
     * [getProducts description]
     *
     * @return  Collection[return description]
     */
    public function getProducts(?string $type = null, ?int $providerId=null, ?int $companyId=null): Collection
    {
        $products = collect();
        $offers = $this->offers;
        
        if($type != null){
            $offers = $offers->where("type", $type);
        }
        if($providerId != null){
            $offers = $offers->where("provider_id", $providerId);
        }

        if($companyId != null){
            $offers = $offers->where("company_id", $companyId);
        }

        foreach ($offers as $offer) {
            $products->push($offer->getUnpricedProducts($type));
        }

        return $products->flatten(1);
    }

    /**
     * [getHotelOffers description]
     *
     * @return  Collection[return description]
     */
    public function getHotelOffers(): ?Collection
    {
        if (!$this->hasHotelOffer()) {
            return null;
        }

        return $this->offers()->where('type', OfferType::HOTEL)->where('status', ProcessStatus::ACTIVE)->get();
    }

    /**
     * [hasHotelOffer description]
     *
     * @return  bool    [return description]
     */
    public function hasHotelOffer(): bool
    {
        return $this->offers()->where('type', OfferType::HOTEL)->where('status', ProcessStatus::ACTIVE)->count() > 0;
    }

    /**
     * [hasBustripOffer description]
     *
     * @return  bool    [return description]
     */
    public function hasBustripOffer(): bool
    {
        return $this->offers()->where('type', OfferType::BUSTRIP)->where('status', ProcessStatus::ACTIVE)->count() > 0;
    }

    /**
     * [hasShuttleOffer description]
     *
     * @return  bool    [return description]
     */
    public function hasShuttleOffer(): bool
    {
        return $this->offers()->where('type', OfferType::SHUTTLE)->where('status', ProcessStatus::ACTIVE)->count() > 0;
    }

    /**
     * [hasShuttleOffer description]
     *
     * @return  bool    [return description]
     */
    public function hasLongtripOffer(): bool
    {
        return $this->offers()->where('type', OfferType::LONGTRIP)->where('status', ProcessStatus::ACTIVE)->count() > 0;
    }

    /**
     * [getHotelOffers description]
     *
     * @return  Collection[return description]
     */
    public function getLongtripOffers(): ?Collection
    {
        if (!$this->hasLongtripOffer()) {
            return null;
        }

        return $this->offers()->where('type', OfferType::LONGTRIP)->where('status', ProcessStatus::ACTIVE)->get();
    }

    /**
     * [getBustripOffers description]
     *
     * @return  Collection[return description]
     */
    public function getBustripOffers(): ?Collection
    {
        if (!$this->hasBustripOffer()) {
            return null;
        }

        return $this->offers()->where('type', OfferType::BUSTRIP)->where('status', ProcessStatus::ACTIVE)->get();
    }

    /**
     * [getShuttleOffers description]
     *
     * @return  Collection[return description]
     */
    public function getShuttleOffers(): ?Collection
    {
        if (!$this->hasShuttleOffer()) {
            return null;
        }

        return $this->offers()->where('type', OfferType::SHUTTLE)->where('status', ProcessStatus::ACTIVE)->get();
    }

    /**
     * [getMapMarkUrl description]
     *
     * @return  string  [return description]
     */
    public function getMapMarkUrl(): ?string
    {
        return 'https://www.amplitur.com.br/imagens/icones/map_ico_show.png';
    }

    /**
     * [getLatitude description]
     *
     * @return  mixed   [return description]
     */
    public function getLatitude()
    {
        return $this->address->latitude;
    }

    /**
     * [getLongitude description]
     *
     * @return  mixed   [return description]
     */
    public function getLongitude()
    {
        return $this->address->longitude;
    }

    /**
     * [getGallery description]
     *
     * @return  [type]  [return description]
     */
    public function getGallery()
    {
        return $this->event->images()->where('is_default', false)->get();
    }


    /**
     * [getGateway description]
     *
     * @return  string  [return description]
     */
    public function getGateway(): ?string
    {
        $paymentMethod = $this->paymentMethods()
            ->where('payment_method_id', $this->payment_method_id)
            ->withPivot('processor')
            ->first();

        if (!empty($paymentMethod) && !empty($paymentMethod->pivot) && !empty($paymentMethod->pivot->processor)) {
            return $paymentMethod->pivot->processor;
        }

        return null;
    }
}
