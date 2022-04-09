<?php

namespace App\Repositories;

use App\Enums\OfferType;
use App\Events\OfferCreatedEvent;
use App\Events\OfferDestroyEvent;
use App\Events\OfferUpdateDependenciesEvent;
use App\Events\OfferUpdatedEvent;
use App\Models\Additional;
use App\Models\AdditionalGroup;
use App\Models\Company;
use App\Models\LongtripAccommodation;
use App\Models\LongtripAccommodationsPricing;
use App\Models\LongtripBoardingLocation;
use App\Models\Offer;
use App\Models\Package;
use App\Repositories\Traits\HasAddress;
use App\Repositories\Traits\Additionalable;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class OfferRepository extends Repository
{

    use HasAddress,
        Additionalable;

    /**
     * @var Company
     */
    protected $company;

    /**
     * @var package
     */
    protected $package;

    /**
     * User instance that is making the request
     *
     * @var User|Provider|null
     */
    protected $actor;

    public function __construct(Offer $model)
    {
        $this->model = $model;
    }

    /**
     * Set repository Company relation
     *
     * @param   Company           $company
     *
     * @return  CompanyRepository
     */
    public function setCompany(Company $company): OfferRepository
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Set repository Package relation
     *
     * @param   Package           $package
     *
     * @return  CompanyRepository
     */
    public function setPackage(Package $package): OfferRepository
    {
        $this->package = $package;

        return $this;
    }

    /**
     * [setActor description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  OfferRepository[return description]
     */
    public function setActor($user): OfferRepository
    {
        $this->actor = $user;

        return $this;
    }

    /**
     * List all items of a resource
     *
     * @return  Collection
     */
    public function list(int $paginate = null, array $_params = null): Collection
    {
        if (!empty($this->actor) && $this->actor->isProvider()) {
            $query = $this->model->where('provider_id', $this->actor->id);
        } else {
            $query = $this->model;
        }

        if (!empty($this->company)) {
            $query = $query->where('company_id', $this->company->id);
        }

        if ($paginate) {
            $collection = $query->paginate($paginate);
        } else {
            $collection = $query->get();
        }

        $collection = $this->onAfterList($collection);

        return $collection;
    }

    /**
     * [listTop10 description]
     *
     * @return  Collection[return description]
     */
    public function listTop10(): Collection
    {
        if (!empty($this->actor) && $this->actor->isProvider()) {
            $query = $this->model->where('provider_id', $this->actor->id);
        } else {
            $query = $this->model;
        }

        return $query->limit(10)->orderByDesc('created_at')->get();
    }

    /**
     * @inherited
     */
    public function onBeforeDelete(Model $resource): Model
    {
        switch(get_class($resource)){
            case Additional::class:
                $count = $resource->offer->bookings()->count();
            break;
            default:
                $count = $resource->bookings()->count();
                break;
        }
        if($count){
            throw new Exception("This offer have bookings and cannot be removed");
        }
        OfferDestroyEvent::dispatch($resource);

        return $resource;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['sale_coefficient_id'] = app(SaleCoefficientRepository::class)->getDefaultCoefficient()->id ?? null;
        
        $attributes['company_id'] = $this->company->id;
        $attributes['provider_id'] = $this->company->provider_id;

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        if ($attributes['type'] == OfferType::HOTEL) {
            app(HotelOffersRepository::class)->createFromOffer($resource, $attributes);
        }

        OfferCreatedEvent::dispatch($resource->fresh());

        return $resource;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $this->handleAdditionalsAssociation($resource, $attributes);
    
        if ($resource->isHotel() && 
            !(user()->isProvider() && $resource->hasBookings())) {
            $attributes['hotel']['address'] = $attributes['address'];
            app(HotelOffersRepository::class)->update($resource->hotelOffer, $attributes['hotel_offer']);
            app(HotelRepository::class)->update($resource->hotelOffer->hotel()->first(), $attributes['hotel']);
        }

        if($resource->isLongTrip()){
            if(isset($attributes["longtrip_accommodations_pricing"])
                && is_array($attributes["longtrip_accommodations_pricing"])
            ){
                foreach($attributes["longtrip_accommodations_pricing"] as $key => $pricings){
                    $price      = sanitizeMoney($pricings['price']);
                    $attributes["longtrip_accommodations_pricing"][$key]["price"] = $price;
                }
            }
        }
        OfferUpdatedEvent::dispatch($resource->fresh());

        return $attributes;
    }

    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        if ($resource->type == OfferType::HOTEL && !empty($attributes['pricing'])) {
            $this->handleHotelAccommodationPricing($resource, $attributes['pricing']);
        }

        if ($resource->type == OfferType::LONGTRIP && !empty($attributes['longtrip_accommodations_pricing'])) {
            $this->handleLongtripAccommodationPricing($resource, $attributes['longtrip_accommodations_pricing']);
        }

        if($resource->isAdditional()){
            if(isset($attributes["additional_id"]) && is_array($attributes["additional_id"])){
                foreach($attributes["additional_id"] as $key => $additional){
                    $entity = app(AdditionalRepository::class)->find($additional);
                    $_data["price"]     = $attributes["price"][$key];
                    $_data["stock"]     = $attributes["stock"][$key];
                    $_data["keep_dates"] = 1;
                    $this->updateAdditionalItem($resource, $entity, $_data);
                }
            }
        }

        // Update dependencies like package_id
        event(new OfferUpdateDependenciesEvent($resource->fresh(), $this));

        return $resource->fresh();
    }

    /**
     * [findByPackageId description]
     *
     * @param   int  $packageId  [$packageId description]
     *
     * @return  [type]           [return description]
     */
    public function findByPackageId(int $packageId, bool $withAdditionals = false)
    {
        $query = $this->model->where('package_id', $packageId)
            ->where('status', 'active')
            ->where('expires_at', '>=', Carbon::now()->format('Y-m-d H:i:s'));

        if (!$withAdditionals) {
            $query = $query->whereIn('type', [OfferType::HOTEL, OfferType::BUSTRIP, OfferType::SHUTTLE, OfferType::LONGTRIP]);
        }

        $offers = $query->get();

        return $offers;
    }

    /**
     * [findByProviderId description]
     *
     * @param   int  $providerId  [$providerId description]
     *
     * @return  [type]           [return description]
     */
    public function findByProviderId(int $providerId, bool $withAdditionals = false)
    {
        $query = $this->model->where('provider_id', $providerId);

        if (!$withAdditionals) {
            $query = $query->whereIn('type', [OfferType::HOTEL, OfferType::BUSTRIP, OfferType::SHUTTLE, OfferType::LONGTRIP]);
        }

        $offers = $query->get();

        return $offers;
    }

    /**
     * [handleHotelAccommodationPricing description]
     *
     * @param   Offer              $offer     [$offer description]
     * @param   array              $pricings  [$pricings description]
     *
     * @return  SupportCollection             [return description]
     */
    public function handleHotelAccommodationPricing(Offer $offer, array $pricings): SupportCollection
    {        
        return app(HotelAccommodationsPricingRepository::class)->sync($offer, $pricings);
    }

    /**
     * [handleLongtripAccommodationPricing description]
     *
     * @param   Offer  $offer                   [$offer description]
     * @param   array  $longtripAccommodations  [$longtripAccommodations description]
     *
     * @return  void                            [return description]
     */
    public function handleLongtripAccommodationPricing(Offer $offer, array $longtripAccommodationsPrincings): void
    {      
        $repository = app(LongtripAccommodationsPricingRepository::class);

        $repository->sync($offer, $longtripAccommodationsPrincings);
    }

    /**
     * [handleAdditionalsAssociation description]
     *
     * @param   Offer  $offer       [$offer description]
     * @param   array  $attributes  [$attributes description]
     *
     * @return  [type]              [return description]
     */
    public function handleAdditionalsAssociation(Offer $offer, array $attributes)
    {
        $additionalables = $this->getAdditionalables($offer);

        foreach ($additionalables as $additionable) {
            $this->associateAdditionals($additionable, $attributes);
        }
    }

    /**
     * [getAdditionalables description]
     *
     * @param   Offer              $offer  [$offer description]
     *
     * @return  SupportCollection          [return description]
     */
    public function getAdditionalables(Offer $offer): SupportCollection
    {
        switch ($offer->type) {
            case OfferTYpe::BUSTRIP:
                return $this->getBoardingLocations($offer);
            case OfferType::SHUTTLE:
                return $this->getBoardingLocations($offer);
            case OfferType::LONGTRIP:
                return $this->getRoutes($offer);
            case OfferTYpe::HOTEL:
                return $this->getHotelAccommodations($offer);
            default:
                return collect();
        }
    }

    public function getRoutes(Offer $offer): SupportCollection
    {
        if (
            $offer->type != OfferType::BUSTRIP &&
            $offer->type != OfferType::SHUTTLE &&
            $offer->type != OfferType::LONGTRIP)
        {
            return collect();
        }

        if ($offer->type == OfferType::BUSTRIP) {
            return $offer->bustripRoutes;
        }

        if ($offer->type == OfferType::SHUTTLE) {
            return $offer->shuttleRoutes;
        }

        if ($offer->type == OfferType::LONGTRIP) {
            return $offer->longtripRoutes;
        }

        return collect();
    }

    /**
     * Get all boarding location of all routes from offer
     *
     * @param   Offer       $offer
     *
     * @return  Collection
     */
    public function getBoardingLocations(Offer $offer): SupportCollection
    {
        $boardingLocations = collect();

        if ($offer->type != OfferType::BUSTRIP &&
            $offer->type != OfferType::SHUTTLE && 
            $offer->type != OfferType::LONGTRIP)
        {
            return $boardingLocations;
        }

        foreach ($this->getRoutes($offer) as $routes) {
            if ($offer->type == OfferType::BUSTRIP) {
                $boardingLocations->push($routes->bustripBoardingLocations);
            } else if ($offer->type == OfferType::SHUTTLE) {
                $boardingLocations->push($routes->shuttleBoardingLocations);
            } else if ($offer->type == OfferType::LONGTRIP) {
                $boardingLocations->push($routes->longtripBoardingLocations);
            }
        }
        
        return $boardingLocations->flatten(1);
    }

    /**
     * [getHotelAccommodations description]
     *
     * @param   Offer              $offer  [$offer description]
     *
     * @return  SupportCollection          [return description]
     */
    public function getHotelAccommodations(Offer $offer): SupportCollection
    {
        $hotelAccommodations = collect();

        if ($offer->type != OfferType::HOTEL || !$offer->hotelOffer) {
            return $hotelAccommodations;
        }

        foreach ($offer->hotelOffer->accommodations as $hotelAccommodation) {
            $hotelAccommodations->push($hotelAccommodation);
        }

        return $hotelAccommodations;
    }

    public function getHotelAccommodationsProducts(Offer $offer): SupportCollection
    {
        $products = collect();

        if (!$offer->isHotel()) {
            return $products;
        }

        $hotelAccommodations = $this->getHotelAccommodations($offer);

        foreach ($hotelAccommodations as $hotelAccommodation) {
            foreach ($hotelAccommodation->hotelAccommodationsPricings()->get() as $pricing) {
                $products->push([
                    'id' => $hotelAccommodation->id,
                    'offer_id' => $offer->id,
                    'offer' => $offer,
                    'product_type' => $hotelAccommodation->hotel_accommodation_type_id,
                    'type' => $hotelAccommodation->getOfferType(),
                    'price' => $pricing->price,
                    'capacity' => $hotelAccommodation->getCapacity(),
                    'title' => mb_strtoupper($hotelAccommodation->getTitle()),
                    'hotel' => $offer->hotel,
                    'checkin' => $pricing->checkin,
                    'checkout' => $pricing->checkout,
                ]);
            }
        }

        return $products;
    }

    /**
     * Get offer linked additionals
     *
     * @param   Offer            $offer
     *
     * @return  SuperCollection 
     */
    public function getAdditionals(Offer $offer): SupportCollection
    {
        if ($offer->type == OfferType::BUSTRIP) {
            $boardingLocations = $this->getBoardingLocations($offer);
            return app(BustripBoardingLocationRepository::class)->getAdditionals($boardingLocations);
        }

        if ($offer->type == OfferType::HOTEL) {
            return collect();
        }
        
        return collect();
    }

    /**
     * [getGroupedProductAdditionals description]
     *
     * @param   Offer  $offer             [$offer description]
     * @param   [type] $productId         [$productId description]
     * @param   [type] $dates             [$dates description]
     * @param   bool   $onlyActiveOffers  [$onlyActiveOffers description]
     * @param   false                     [ description]
     *
     * @return  [type]                    [return description]
     */
    public function getGroupedProductAdditionals(Offer $offer, $productId, $dates, bool $onlyActiveOffers = false)
    {
        $product = $this->getProduct($offer, $productId);
        $dates = collect($dates)->pluck('date')->toArray();

        if (empty($dates) && $offer->isHotel()) {
            return null;
        }

        if (!empty($dates)) {
            $additionals = $product->additionals();

            $additionals->where(function ($query) use ($dates) {
                foreach ($dates as $date) {
                    $query->orWhere(function ($query) use ($date) {
                        $query->whereJsonContains('fields->sale_dates', $date);
                    });
                }
            });

            $additionals = $additionals->orWhere(function ($query) {
                $query->orWhere('fields->sale_dates', null);
            });

            $additionals = $additionals->get();

            if (!empty($additionals)) {
                $additionals = $additionals->unique();
            }

        } else {
            if ($product instanceof LongtripAccommodationsPricing) {
                $additionals = $product->longtripRoute->additionals;
            } else {
                $additionals = $product->additionals;
            }
        }
        
        if($additionals){ 
            $additionals = $additionals->filter(function ($additional, $key) {
                return $additional->isActive(); 
            });

            $additionals = $additionals->groupBy('additional_group_id'); 
        }

        if (empty($additionals)) {
            return null;
        }

        return $additionals;
    }

    public function getProduct(Offer $offer, $productId)
    {
        if ($offer->isHotel()) {
            $products = $this->getHotelAccommodations($offer);
        } else if ($offer->isBustrip() || $offer->isShuttle()) {
            $products = $this->getBoardingLocations($offer);
        } else if ( $offer->isLongtrip()){
            $products = $offer->longtripAccommodationsPricings;
        }

        return $products->where('id', $productId)->first();
    }

    /**
     * [getProducts description]
     *
     * @param   Offer              $offer  [$offer description]
     *
     * @return  SupportCollection          [return description]
     */
    public function getProducts(Offer $offer): SupportCollection
    {
        $products = collect();
        $resources = [];

        if ($offer->isHotel()) {
            return $this->getHotelAccommodationsProducts($offer);
        } else if ($offer->isBustrip() || $offer->isShuttle() || $offer->isLongtrip()) {
            $resources = $this->getBoardingLocations($offer);
        }

        foreach ($resources as $resource) {
            if (empty($resource)) {
                continue;
            }

            $_data = [
                'id' => $resource->id,
                'offer_id' => $offer->id,
                'offer' => $offer,
                'type' => $resource->getOfferType(),
                'price' => $resource->getPrice(),
                'capacity' => $resource->getCapacity(),
                'title' => $resource->getTitle(),
            ];
            if($offer->isBustrip()){
                $_data['bustrip_route_id'] = $resource->bustrip_route_id;
            }
            if($offer->isShuttle()){
                $_data['shuttle_route_id'] = $resource->shuttle_route_id;
            }
            $products->push($_data);
        }

        return $products;
    }

    public function getUnpricedProducts(Offer $offer, ?string $type = null): SupportCollection
    {
        $products = collect();

        if ($offer->isHotel() && (empty($type) || $type == OfferType::HOTEL)) {
            return $this->getHotelAccommodations($offer);
        } else if ($offer->isBustrip() || $offer->isShuttle()) {
            if (empty($type) || $type == OfferType::BUSTRIP || $type == OfferType::SHUTTLE) {
                return $this->getBoardingLocations($offer);
            }
        } else if ($offer->isLongtrip()) {
            return $this->getLongtripProducts($offer);
        }

        return $products;
    }

    /**
     * [getLongtripProducts description]
     *
     * @param   Offer              $offer  [$offer description]
     *
     * @return  SupportCollection          [return description]
     */
    public function getLongtripProducts(Offer $offer): SupportCollection
    {
        $products = collect();

        $boardingLocationIds = DB::table('longtrip_boarding_locations')
            ->join('longtrip_routes', 'longtrip_boarding_locations.longtrip_route_id', '=', 'longtrip_routes.id')
            ->join('offers', 'longtrip_routes.offer_id', '=', 'offers.id')
            ->where('offers.type', 'longtrip')
            ->where('offers.id', $offer->id)
            ->get('longtrip_boarding_locations.id')
            ->pluck('id');

        $boardingLocations = LongtripBoardingLocation::whereIn('id', $boardingLocationIds->toArray())->get();

        $accommodationPricingIds = DB::table('longtrip_accommodations_pricings')
            ->join('longtrip_routes', 'longtrip_accommodations_pricings.longtrip_route_id', '=', 'longtrip_routes.id')
            ->join('offers', 'longtrip_routes.offer_id', '=', 'offers.id')
            ->where('offers.type', 'longtrip')
            ->where('offers.id', $offer->id)
            ->get('longtrip_accommodations_pricings.id')
            ->pluck('id');

        $accommodationPricing = LongtripAccommodationsPricing::whereIn('id', $accommodationPricingIds->toArray())->get();

        $accommodationPricing = $accommodationPricing->unique(function ($item) {
            return $item['longtrip_accommodation_type_id'] . $item['longtrip_route_id'];
        });

        $products->push($accommodationPricing->flatten(1));
        $products->push($boardingLocations->flatten(1));

        return $products->flatten(1);
    }

    /**
     * [getOfferProduct description]
     *
     * @param   string  $offerType  [$offerType description]
     * @param   int     $productId  [$productId description]
     *
     * @return  [type]              [return description]
     */
    public function getOfferProduct(string $offerType, int $productId)
    {
        $product = null;

        if ($offerType == OfferType::BUSTRIP) {
            $product = app(BustripBoardingLocationRepository::class)->find($productId);
        } else if ($offerType == OfferType::SHUTTLE) {
            $product = app(ShuttleBoardingLocationRepository::class)->find($productId);
        } else if ($offerType == OfferType::HOTEL) {
            $product = app(HotelAccommodationRepository::class)->find($productId);
        } else if ($offerType == OfferType::LONGTRIP) {
            $product = app(LongtripAccommodationsPricingRepository::class)->find($productId);
        }else if ($offerType == OfferType::LONGTRIP_BOARDING_LOCATION) {
            $product = app(LongtripBoardingLocation::class)->find($productId);
        }

        return $product;
    }

    /**
     * [getLongtripAccommodation description]
     *
     * @param   int  $longtripAccommodationId  [$longtripAccommodationId description]
     *
     * @return  [type]                         [return description]
     */
    public function getLongtripAccommodation(int $longtripAccommodationId)
    {
        $longtripAccommodation = app(LongtripAccommodationRepository::class)->find($longtripAccommodationId);

        return $longtripAccommodation;
    }

    /**
     * [getLongtripBoardingLocation description]
     *
     * @param   int  $longtripBoardingLocationId  [$longtripBoardingLocationId description]
     *
     * @return  [type]                            [return description]
     */
    public function getLongtripBoardingLocation(int $longtripBoardingLocationId)
    {
        $longtripBoardingLocation = app(LongtripBoardingLocationRepository::class)->find($longtripBoardingLocationId);

        return $longtripBoardingLocation;
    }

    /**
     * [getLongtripBoardingLocations description]
     *
     * @param   int  $longtripBoardingLocationId  [$longtripBoardingLocationId description]
     *
     * @return  [type]                            [return description]
     */
    public function getLongtripBoardingLocationsByRouteId(int $longtripRouteId)
    {
        $longtripBoardingLocations = app(LongtripBoardingLocation::class)->where("longtrip_route_id", $longtripRouteId)->get();

        return $longtripBoardingLocations;
    }

    /**
     * [getLongtripAccommodationsPricing description]
     *
     * @param   Offer  $offer  [$offer description]
     * @param   [type] $type   [$type description]
     *
     * @return  [type]         [return description]
     */
    public function getLongtripAccommodationsPricing(Offer $offer, $longtripBoardingLocation, $longtripBoardingLocationPricingId)
    {
        return $longtripBoardingLocation->longtripRoute->longtripAccommodationsPricings()->where('id', $longtripBoardingLocationPricingId)->first();
    }

    /**
     * [getAdditionalItems description]
     *
     * @param   Offer       $offer  [$offer description]
     *
     * @return  Collection          [return description]
     */
    public function getAdditionalItems(Offer $offer): Collection
    {
        return $offer->additionals()->get();
    }

    /**
     * [getAdditionalGroups description]
     *
     * @param   Offer       $offer  [$offer description]
     *
     * @return  Collection          [return description]
     */
    public function getAdditionalGroups(Offer $offer): Collection
    {
        return $offer->additionalGroups()->get();
    }

    /**
     * [getAdditionalItemGroup description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  int            [return description]
     */
    public function getAdditionalItemGroup(Offer $offer): ?int
    {
        return app(AdditionalGroup::class)->getOfferTypeMapped($offer);
    }

    /**
     * [storeAdditionalItem description]
     *
     * @param   Offer       $offer       [$offer description]
     * @param   array       $attributes  [$attributes description]
     *
     * @return  Additional               [return description]
     */
    public function storeAdditionalItem(Offer $offer, array $attributes): Additional
    {
        // Allowed Providers
        if(isset($attributes['allowed_companies']) && is_array($attributes['allowed_companies'])){
            foreach($attributes['allowed_companies'] as $allowedCompany){
                $company = Company::find($allowedCompany);
                $attributes['allowed_providers'][]  = (string) $company->provider_id;
            }
        }

        $additional = app(AdditionalRepository::class)->store([
            'provider_id' => $offer->provider_id,
            'package_id' => $offer->package_id,
            'offer_id' => $offer->id,
            'offer' => $offer,
            'additional_group_id' => $attributes['additional_group_id'],
            'name' => $attributes['name'],
            'currency' => $offer->currency,
            'price' => $attributes['price'],
            'stock' => $attributes['stock'],
            'type' => $attributes['type'],
            'fields' => isset($attributes['fields']) ? $attributes['fields'] : null,
            'availability' => $attributes['availability'],
            'allowed_providers' => isset($attributes['allowed_providers']) ? $attributes['allowed_providers'] : null,
            'allowed_companies' => isset($attributes['allowed_companies']) ? $attributes['allowed_companies'] : null,
        ]);

        return $additional;
    }

    /**
     * [updateAdditionalItem description]
     *
     * @param   Offer       $offer       [$offer description]
     * @param   Additional  $additional  [$additional description]
     * @param   array       $attributes  [$attributes description]
     *
     * @return  Additional               [return description]
     */
    public function updateAdditionalItem(Offer $offer, Additional $additional, array $attributes): Additional
    {
        $additionalGroupId = $this->getAdditionalItemGroup($offer);

        if (!$additionalGroupId) {
            throw new Exception("");
        }

        $fields = $additional->fields;

        // Below is for legacy.
        if(!isset($attributes['restrict_update'])){ $attributes['restrict_update'] = true;}
        if($attributes['restrict_update']){
            if (!isset($attributes['fields']['sale_dates']) && (isset($attributes['keep_dates']) && !$attributes['keep_dates'])) {
                $fields['sale_dates'] = null;
            } else if (!$attributes['keep_dates']) {
                $fields['sale_dates'] = $attributes['fields']['sale_dates'];
            } else {
                $fields = $additional->fields;
            }
        }
        
        if (!isset($attributes['type']) && (isset($attributes['keep_dates']) && !$attributes['keep_dates'])) {
            $attributes['type'] = null;
        } else if ($attributes['keep_dates']) {
            $attributes['type'] = $additional->type;
        }

        // Allowed Providers
        if(isset($attributes['allowed_companies']) && is_array($attributes['allowed_companies'])){
            foreach($attributes['allowed_companies'] as $allowedCompany){
                $company = Company::find($allowedCompany);
                $attributes['allowed_providers'][]  = (string) $company->provider_id;
            }
        }

        $additional = app(AdditionalRepository::class)->update($additional, [
            'additional_group_id' => isset($attributes['additional_group_id']) ? $attributes['additional_group_id'] : $additional->additional_group_id,
            'name' => isset($attributes['name']) ? $attributes['name']: $additional->name,
            'price' => $attributes['price'],
            'stock' => $attributes['stock'],
            'type' => $attributes['type'],
            'fields' => $fields,
            'availability' => isset($attributes['availability']) ? $attributes['availability'] : $additional->availability,
            'allowed_providers' => isset($attributes['allowed_providers']) ? $attributes['allowed_providers'] : $additional->allowed_providers,
            'allowed_companies' => isset($attributes['allowed_companies']) ? $attributes['allowed_companies'] : $additional->allowed_companies,
        ]);

        return $additional;
    }

    /**
     * [storeAdditionalGroup description]
     *
     * @param   Offer            $offer       [$offer description]
     * @param   array            $attributes  [$attributes description]
     *
     * @return  AdditionalGroup               [return description]
     */
    public function storeAdditionalGroup(Offer $offer, array $attributes): AdditionalGroup
    {
        $additionalGroupId = $this->getAdditionalItemGroup($offer);

        if (!$additionalGroupId) {
            throw new Exception("");
        }

        $additional = app(AdditionalGroupRepository::class)->store([
            'provider_id' => $offer->provider_id,
            'package_id' => $offer->package_id,
            'offer_id' => $offer->id,
            'offer' => $offer,
            'additional_group_id' => $additionalGroupId,
            'name' => $attributes['name'],
            'internal_name' => $attributes['internal_name'],
            'selection_type' => $attributes['selection_type'],
        ]);

        return $additional;
    }

    /**
     * [updateAdditionalGroup description]
     *
     * @param   Offer            $offer            [$offer description]
     * @param   AdditionalGroup  $additionalGroup  [$additionalGroup description]
     * @param   array            $attributes       [$attributes description]
     *
     * @return  AdditionalGroup                    [return description]
     */
    public function updateAdditionalGroup(Offer $offer, AdditionalGroup $additionalGroup, array $attributes): AdditionalGroup
    {
        $additionalGroupId = $this->getAdditionalItemGroup($offer);

        if (!$additionalGroupId) {
            throw new Exception("");
        }

        $additional = app(AdditionalGroupRepository::class)->update($additionalGroup, [
            'name' => $attributes['name'],
            'internal_name' => $attributes['internal_name'],
            'selection_type' => $attributes['selection_type'],
        ]);

        return $additional;
    }

    /**
     * [destroyAdditionalGroup description]
     *
     * @param   Offer            $offer            [$offer description]
     * @param   AdditionalGroup  $additionalGroup  [$additionalGroup description]
     * @param   array            $attributes       [$attributes description]
     *
     * @return  AdditionalGroup                    [return description]
     */
    public function destroyAdditionalGroup(Offer $offer, AdditionalGroup $additionalGroup): AdditionalGroup
    {
        if($additionalGroup->additionals){
            foreach($additionalGroup->additionals as $additional){
                app(AdditionalRepository::class)->delete($additional);
            }
        }
        $additional = app(AdditionalGroupRepository::class)->delete($additionalGroup);

        return $additional;
    }

    /**
     * [destroyAdditional description]
     *
     * @param   Offer            $offer            [$offer description]
     * @param   AdditionalGroup  $additionalGroup  [$additionalGroup description]
     * @param   array            $attributes       [$attributes description]
     *
     * @return  AdditionalGroup                    [return description]
     */
    public function destroyAdditional(Offer $offer, Additional $additional): Additional
    {
        app(AdditionalRepository::class)->delete($additional);

        return $additional;
    }

    /**
     * [getBookableProducts description]
     *
     * @param   Offer  $offer  [$offer description]
     * @param   array  $dates  [$dates description]
     *
     * @return  [type]         [return description]
     */
    public function getBookableProducts(Offer $offer, array $dates)
    {
        if (empty($dates)) {
            return [];
        }

        $carbonDates = [];

        foreach ($dates as $date) {
            $carbonDates[] = Carbon::createFromFormat('Y-m-d', $date)->setTime(0, 0, 0, 0);
        }

        $products = collect($this->getProducts($offer));
        $products = $products->whereIn('checkin', $carbonDates);
        $groupedProducts = $products->groupBy('capacity');

        $pricedGroupedProducts = [];

        foreach ($groupedProducts as $key => $products) {

            $pricedGroupedProducts[$key] = [
                'id' => $products[0]['id'],
                'type' => $offer->type,
                'price' => 0,
                'capacity' => $products[0]['capacity'],
                'title' => $products[0]['title'],
                'hotel' => $offer->hotel,
                'checkin' => $carbonDates[0]->format('Y-m-d'),
                'checkout' => $carbonDates[sizeof($carbonDates) - 1]->format('Y-m-d'),
            ];

            foreach ($products as $product) {
                $pricedGroupedProducts[$key]['price'] += $product['price'];
            }
        }

        return $pricedGroupedProducts;
    }

    /**
     * [getGroupedProducts description]
     *
     * @param   Offer  $offer  [$offer description]
     *
     * @return  [type]         [return description]
     */
    public function getGroupedProducts(Offer $offer, $product = null)
    {
        $products = collect($this->getProducts($offer));

        if ($offer->isHotel()) {
            $products = $this->getHotelAccommodations($offer);

            return $products;
        }

        if ($offer->isLongtrip()) {
            $products = $this->getLongtripBoadingLocations($offer, $product);

            return $products;
        }

        return [];
    }

    public function getLongtripRouteAccommodationsType($boardingLocationId, $accommodationTypeId)
    {
        $longtripBoardingLocation = app(LongtripBoardingLocationRepository::class)->find($boardingLocationId);

        if (empty($longtripBoardingLocation)) {
            return null;
        }

        $longtripAccommodations = LongtripAccommodation::where('longtrip_route_id', $longtripBoardingLocation->longtripRoute->id)
            ->where('longtrip_accommodation_type_id', $accommodationTypeId)
            ->first();

        return $longtripAccommodations;
    }

    /**
     * [getLongtripAccommodationsPricings description]
     *
     * @param   [type]  $offer                     [$offer description]
     * @param   [type]  $longtripBoardingLocation  [$longtripBoardingLocation description]
     *
     * @return  [type]                             [return description]
     */
    public function getLongtripBoadingLocations($offer, $longtripBoardingLocation)
    {
        $longtripBoardingLocation = app(LongtripBoardingLocationRepository::class)->find($longtripBoardingLocation);

        if (empty($longtripBoardingLocation)) {
            return null;
        }

        return $longtripBoardingLocation->longtripRoute->longtripAccommodationsPricings;
    }

    /**
     * [getPaymentTotal description]
     *
     * @param   Package  $package        [$package description]
     * @param   Offer    $offer          [$offer description]
     * @param   array    $additionalIds  [$additionalIds description]
     * @param   object   $product        [$product description]
     * @param   array    $dates          [$dates description]
     * @param   int      $passengers     [$passengers description]
     *
     * @return  [type]                   [return description]
     */
    public function getPaymentTotal(?Package $package, ?Offer $offer, ?array $additionalIds, $product = null, ?array $dates, int $passengers = 1, int $netPrice=0)
    {
        if (empty($package) || empty($offer) || empty($product)) {
            return 0;
        }

        $total = 0;

        if (!empty($dates)) {
            for ($i = 0; $i < $passengers; $i++) {
                foreach ($dates as $date) {
                    if($netPrice){
                        $price = $product->getPriceNet($date);
                    }else{
                        $price = $product->getPrice($date);
                    }
                    $total += moneyFloat($price, currency(), $offer->currency);
                }
            }
        }

        if (!empty($product) && empty($dates)) {
            for ($i = 0; $i < $passengers; $i++) {
                if (is_array($product)) {
                    foreach ($product as $p) {
                        if($p == null){ continue;}
                        if($netPrice){
                            $price = $p->getPriceNet();
                        }else{
                            $price = $p->getPrice();
                        }
                        $total += moneyFloat($price, currency(), $offer->currency);    

                    }
                } else {
                    if($netPrice){
                        $price = $product->getPriceNet();
                    }else{
                        $price = $product->getPrice();
                    }
                    $total += moneyFloat($price, currency(), $offer->currency);
                }
            }
        }

        if (!empty($additionalIds)) {
            foreach ($additionalIds as $additionalIdGroup) {
                $additionals = Additional::where('id', $additionalIdGroup)->get();

                foreach ($additionals as $additional) {
                    if($netPrice){
                        $price = $additional->getPriceNet();
                    }else{
                        $price = $additional->getPrice();
                    }
                    $total += moneyFloat($price, currency(), $additional->currency);
                }
            }
        }

        return $total;
    }

    /**
     * [getOfferTotal description]
     *
     * @param   Package  $package  [$package description]
     * @param   Offer    $offer    [$offer description]
     * @param   [type]   $product  [$product description]
     * @param   array    $dates    [$dates description]
     * @param   bool    $netPrice    [$netPrice description]
     *
     * @return  [type]             [return description]
     */
    public function getOfferTotal(Package $package, Offer $offer, $product, ?array $dates, ?bool $netPrice=false)
    {
        return $this->getPaymentTotal($package, $offer, null, $product, $dates, 1, $netPrice);
    }
}