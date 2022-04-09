<?php

namespace App\Repositories;

use App\Enums\AccessStatus;
use App\Enums\DisplayType;
use App\Enums\OfferType;
use App\Enums\ProcessStatus;
use App\Events\PackageCreatedEvent;
use App\Events\PackageUpdatedEvent;
use App\Models\Company;
use App\Models\Event;
use App\Models\Offer;
use App\Models\Package;
use App\Models\PaymentMethod;
use App\Models\Provider;
use App\Repositories\Traits\HasAddress;
use Carbon\Carbon;
use DateInterval;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class PackageRepository extends Repository
{
    use HasAddress;

    /**
     * @var Event
     */
    protected $event;

    protected $previousStartsAt;

    protected $previousEndsAt;

    /**
     * User instance that is making the request
     *
     * @var User|Provider|null
     */
    protected $actor;
 
    public function __construct(Package $model)
    {
        $this->model = $model;
    }

    /**
     * Set event instance based on object or id
     *
     * @param   Event|int  $event
     *
     * @return  PackageRepository
     */
    public function setEvent($event): PackageRepository
    {
        if ($event instanceof Event) {
            $this->event = $event;
        } else {
            $this->event = app(EventRepository::class)->find($event);
        }

        return $this;
    }

    /**
     * [setActor description]
     *
     * @param   [type]  $user  [$user description]
     *
     * @return  PackageRepository[return description]
     */
    public function setActor($user): PackageRepository
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

        if(isset($_params['analytic'])){
            $query = $query
            ->with(['event'])
            ->orderBy("packages.provider_id", "ASC")
                ;
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

        return $query->limit(10)->orderByDesc('visits')->get();
        
    }

    /**
     * Get available packages for specific event
     *
     * @return  Collection
     */
    public function getAvailables(): Collection
    {
        return $this->event->availablePackages()->get();
    }

    /**
     * [listActive description]
     *
     * @return  Collection[return description]
     */
    public function listActive(): Collection
    {
        return $this->model
            ->where('status', 'active')
            ->where('display_type', DisplayType::PUBLIC)
            ->whereDate('ends_at', '>=', Carbon::today()->format('Y-m-d'))
            ->limit(6)
            ->orderByRaw("RAND()")
            ->get();
    }

    /**
     * [listPendingNextPackages description]
     *
     * @param   Event       $event  [$event description]
     *
     * @return  Collection          [return description]
     */
    public function listPendingNextPackages(?Event $event = null): Collection
    {
        $query = $this->model
            ->whereIn('status', ['active', 'in-analysis'])
            ->whereDate('ends_at', '>=', Carbon::today()->format('Y-m-d'))
            ->get();

        if (!empty($event)) {
            $query = $query->where('event_id', $event->id);
        }

        return $query->get();
    }

    /**
     * [listNextPackages description]
     *
     * @param   Package     $packageExcept  [$packageExcept description]
     *
     * @return  Collection                  [return description]
     */
    public function listNextPackages(?Package $packageExcept = null): Collection
    {
        $query = $this->model
            ->where('status', 'active')
            ->where('display_type', DisplayType::PUBLIC)
            ->whereDate('ends_at', '>=', Carbon::today()->format('Y-m-d'))
            ->orderBy("ends_at", "ASC")
            ->limit(10);

        if (!empty($packageExcept)) {
            $query = $query->where('id', '!=', $packageExcept->id);
        }

        return $query->get();
    }

    /**
     * [listTopPackages description]
     *
     * @param   Package     $packageExcept  [$packageExcept description]
     *
     * @return  Collection                  [return description]
     */
    public function listTopPackages(?Package $packageExcept = null): Collection
    {
        $query = $this->model
            ->where('status', 'active')
            ->where('display_type', DisplayType::PUBLIC)
            ->whereDate('ends_at', '>=', Carbon::today()->format('Y-m-d'))
            ->orderBy("visits", "DESC")
            ->limit(10);

        if (!empty($packageExcept)) {
            $query = $query->where('id', '!=', $packageExcept->id);
        }

        return $query->get();
    }

    /**
     * Get package by token
     *
     * @param   string   $token
     *
     * @return  Package|null
     */
    public function findByToken(string $token): ?Package
    {
        return $this->model->where('token', $token)->first();
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

        $entities = $query->get();

        return $entities;
    }

    /**
     * Get package offers
     *
     * @param   Package     $package
     *
     * @return  Collection
     */
    public function getOffers(Package $package, string $orderBy = 'created_at'): Collection
    {
        return $package->offers()->orderBy($orderBy)->get();
    }


    /**
     * Get providers by package
     *
     * @param   Package     $package
     *
     * @return  Collection
     */
    public function getProvidersByPackage(Package $package): Collection
    {
        $fields = ["provider_id"];
        $providers = $package->offers()->select("provider_id")->groupBy("provider_id")->get()->pluck("provider_id");
        return (new Provider())->whereIn("id", $providers)->get();
    }

    /**
     * Get companies by package
     *
     * @param   Package     $package
     *
     * @return  Collection
     */
    public function getCompaniesByPackage(Package $package): Collection
    {
        $companies = $package->offers()->select("company_id")->groupBy("company_id")->get()->pluck("company_id");
        return (new Company())->whereIn("id", $companies)->get();
    }

    /**
     * Get package offers
     *
     * @param   Package     $package
     *
     * @return  Collection
     */
    public function getOffersActive(Package $package, string $orderBy = 'created_at'): Collection
    {
        return $package->offers()->where('status', AccessStatus::ACTIVE)->orderBy($orderBy)->get();
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes['meta_keywords']        = $this->event->getTranslations('meta_keywords');
        $attributes['meta_description']     = $this->event->getTranslations('meta_description');
        $attributes['description']          = $this->event->getTranslations('description');
        $attributes['payment_expire_days']  = app(PackageTemplateRepository::class)->first()->payment_expire_days;
        $attributes['name']                 = $this->event->name;
        $attributes = $this->handleDisplayType($attributes);

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $this->previousStartsAt = $resource->starts_at;
        $this->previousEndsAt = $resource->ends_at;

        $attributes = $this->handleDisplayType($attributes, $resource);

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $this->handleAddress($resource, $attributes['address']);
        $this->handlePaymentMethods($resource);

        PackageCreatedEvent::dispatch($resource->fresh());

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $this->handleAddress($resource, $attributes['address']);
        $this->handlePaymentMethods($resource, $attributes);

        $this->handleUpdateDependencies($resource);

        PackageUpdatedEvent::dispatch($resource->fresh());

        // For now, it will updated here
        if($resource->hasBookings()){
            foreach($resource->bookings as $booking){
                $booking->setStatus(ProcessStatus::IN_ANALYSIS);
                $booking->save();
            }
            foreach($resource->offers as $offer){
                $offer->setStatus(ProcessStatus::IN_ANALYSIS);
                $offer->save();
            }
        }

        return $resource;
    }

    /**
     * Inject token when displau type is non-listed
     *
     * @param   array  $attributes
     *
     * @return  array
     */
    protected function handleDisplayType(array $attributes, Package $package = null): array
    {
        if (!isset($attributes['display_type'])) { 
            $attributes['display_type'] = DisplayType::PUBLIC;
        }

        if ((empty($package) || empty($package->token)) && $attributes['display_type'] == DisplayType::NON_LISTED) {
            $attributes['token'] = token();

            if (!empty($this->findByToken($attributes['token']))) {
                $attributes = $this->handleDisplayType($attributes);
            }
        } else {
            if($attributes['display_type'] != DisplayType::NON_LISTED){
                $attributes['token'] = null;
            }
        }

        return $attributes;
    }

    /**
     * Get all additionals from package
     *
     * @param   Offer       $offer
     *
     * @return  Collection
     */
    public function getAdditionals(Offer $offer): SupportCollection
    {
        $additionals = $offer->package->additionals()
            ->where(function ($query) use ($offer) {
                $query->orWhere('availability', 'public');
                $query->orWhere(function ($query) use ($offer) {
                    $query->where('availability', 'exclusive');
                    $query->whereRaw('JSON_CONTAINS(allowed_providers, \'"' . $offer->provider_id . '"\')');
                });
            })
            ->where(function ($query) use ($offer) {
                $query->orWhere('type', '=', null);
                $query->orWhereRaw('JSON_CONTAINS(type, \'"' . $offer->type . '"\')');
            });
        $additionals = $additionals->get();

        return $additionals;
    }

    /**
     * Get all additionals from package
     *
     * @param   Offer       $offer
     *
     * @return  Collection
     */
    public function getAdditionalByProvider(Offer $offer, ?Provider $provider): SupportCollection
    {
        if($provider == null){ return collect();}
        
        $additionals = $offer->package->additionals()
            ->where("provider_id", $provider->id)
            ->where(function ($query) use ($offer) {
                $query->orWhere('availability', 'public');
                $query->orWhere(function ($query) use ($offer) {
                    $query->where('availability', 'exclusive');
                    $query->whereRaw('JSON_CONTAINS(allowed_providers, \'"' . $offer->provider_id . '"\')');
                });
            })
            ->where(function ($query) use ($offer) {
                $query->orWhere('type', '=', null);
                $query->orWhereRaw('JSON_CONTAINS(type, \'"' . $offer->type . '"\')');
            });
        $additionals = $additionals->get();

        return $additionals;
    }

        /**
     * Get all additionals from package
     *
     * @param   Offer       $offer
     *
     * @return  Collection
     */
    public function getAdditionalByCompany(Offer $offer, ?Company $company): SupportCollection
    {
        if($company == null){ return collect();}
        $additionals = $offer->package->additionals()
            ->select("additionals.*")
            ->join("offers", "offers.id", "=", "additionals.offer_id")
            ->where("offers.company_id", $company->id)
            ->where("additionals.provider_id", $company->provider_id)
            ->where(function ($query) use ($offer, $company) {
                $query->orWhere('availability', 'public');
                $query->orWhere(function ($query) use ($offer, $company) {
                    $query->where('availability', 'exclusive');
                    $query->whereRaw('JSON_CONTAINS(allowed_providers, \'"' . $company->provider_id . '"\')');
                });
            })
            ->where(function ($query) use ($offer) {
                $query->orWhere('additionals.type', '=', null);
                $query->orWhereRaw('JSON_CONTAINS(additionals.type, \'"' . $offer->type . '"\')');
            });
        $additionals = $additionals->get();

        return $additionals;
    }
    

    /**
     * [handlePaymentMethods description]
     *
     * @param   Package  $package  [$package description]
     *
     * @return  [type]             [return description]
     */
    public function handlePaymentMethods(Package $package, ?array $attributes = null)
    {
        $pivotPaymentMethods = isset($attributes['payment_methods']) ? $attributes['payment_methods'] : [];
        $pivotPaymentMethods = !empty($pivotPaymentMethods) ? collect($pivotPaymentMethods) : [];
        
        if (!empty($pivotPaymentMethods)) {

            // Upading MAIN package payment methods
            foreach ($pivotPaymentMethods as $pivotPaymentMethod) {
                $pivotPaymentMethod['tax'] = sanitizeMoney($pivotPaymentMethod['tax']);
                $pivotPaymentMethod['discount'] = sanitizeMoney($pivotPaymentMethod['discount']);
                $pivotPaymentMethodId = $pivotPaymentMethod['id'];
                unset($pivotPaymentMethod['id']);

                if (!$pivotPaymentMethod['first_installment_billet']) {
                    $pivotPaymentMethod['first_installment_billet_method_id'] = null;
                    $pivotPaymentMethod['first_installment_billet_processor'] = null;
                } 

                $package->paymentMethods()->updateExistingPivot($pivotPaymentMethodId, $pivotPaymentMethod);
            }

            // Upadting DEPENDENT package first installment with billet payment methods
            foreach ($pivotPaymentMethods as $pivotPaymentMethod) {
                if ($pivotPaymentMethod['first_installment_billet']) {
                    $firstBilletPaymentMethod = $package->paymentMethods()
                        ->withPivot('processor')
                        ->where('payment_method_id', $pivotPaymentMethod['first_installment_billet_method_id'])
                        ->first();

                    $package->paymentMethods()->updateExistingPivot($pivotPaymentMethod['id'], [
                        'first_installment_billet_processor' => $firstBilletPaymentMethod ? $firstBilletPaymentMethod->pivot->processor : null,
                    ]);
                }
            }

            return;
        }

        $paymentMethodTemplates = app(PaymentMethodTemplateRepository::class)->list();

        foreach ($paymentMethodTemplates as $paymentMethodTemplate) {
            $paymentMethodAttributes = [
                'processor' => $paymentMethodTemplate->processor,
                'tax' => parseNumber($paymentMethodTemplate->tax),
                'discount' => parseNumber($paymentMethodTemplate->discount),
                'limiter' => $paymentMethodTemplate->limiter,
                'max_installments' => $paymentMethodTemplate->max_installments,
                'first_installment_billet' => $paymentMethodTemplate->first_installment_billet,
                'first_installment_billet_method_id' => $paymentMethodTemplate->first_installment_billet_method_id,
                'first_installment_billet_processor' => $paymentMethodTemplate->first_installment_billet_processor,
                'is_active' => true,
            ];

            $package->paymentMethods()->attach($paymentMethodTemplate->payment_method_id, $paymentMethodAttributes);
        }
    }

    /**
     * [getPackagePaymentMethods description]
     *
     * @param   Package  $package  [$package description]
     *
     * @return  [type]             [return description]
     */
    public function getPackagePaymentMethods(Package $package)
    {
        return $package->paymentMethods()->withPivot(['processor', 'max_installments', 'discount'])->get();
    }

    /**
     * [search description]
     *
     * @param   [type]  $string  [$string description]
     *
     * @return  [type]           [return description]
     */
    public function search($string)
    {
        $packages = $this->model
            ->join('events', 'packages.event_id', '=', 'events.id')
            ->where('events.name', 'like', "%{$string}%")
            ->where('packages.status', 'active')
            ->whereDate('ends_at', '>=', Carbon::today()->format('Y-m-d'))
            ->orderBy('packages.updated_at')
            ->get('packages.*');

        return $packages;
    }

    /**
     * [visit description]
     *
     * @param   Package  $package  [$package description]
     *
     * @return  int                [return description]
     */
    public function visit(Package $package): int
    {
        $package->visits += 1;
        $package->save();

        return $package->visits;
    }

    /**
     * [handleUpdateDependencies description]
     *
     * @param   Package  $package  [$package description]
     *
     * @return  [type]             [return description]
     */
    public function handleUpdateDependencies(Package $package)
    {
        if ($package->event->hasOneDayDuration() && $this->previousStartsAt != $package->starts_at) {
            $this->updateHotelOffers($package);
            return;
        }

        $previousIntervalDays = $this->previousStartsAt->diffInDays($this->previousEndsAt);
        $intervalDays = $package->starts_at->diffInDays($package->ends_at);

        if ($intervalDays != $previousIntervalDays) {
            return;
        }

        if ($this->previousStartsAt != $package->starts_at && $this->previousEndsAt != $package->ends_at) {
            $this->updateHotelOffers($package);
            return;
        }
    }

    /**
     * [updateHotelOffers description]
     *
     * @param   Package  $package  [$package description]
     *
     * @return  [type]             [return description]
     */
    public function updateHotelOffers(Package $package)
    {
        $offers = $package->offers()->where('type', OfferType::HOTEL)->get();

        foreach ($offers as $offer) {
            foreach ($offer->hotelOffer->accommodations as $accommodation) {
                foreach ($accommodation->hotelAccommodationsPricings as $key => $pricing) {
                    if(!isset($package->bookablePeriod[$key]['date'])){
                        // This needed to be reviewed in the future. 11/02/22
                        continue;
                    }
                    $pricing->update([
                        'checkin' => $package->bookablePeriod[$key]['date'],
                        'checkout' => $package->bookablePeriod[$key]['date']->add(new DateInterval('P1D')),
                    ]);
                }
            }
        }
    }

    /**
     * [removePaymentMethod description]
     *
     * @param   Package        $package        [$package description]
     * @param   PaymentMethod  $paymentMethod  [$paymentMethod description]
     *
     * @return  [type]                         [return description]
     */
    public function removePaymentMethod(Package $package, int $paymentMethodPivotId)
    {
        DB::delete('delete from package_payment_method where id = ?', [$paymentMethodPivotId]);
    }

    /**
     * [addPaymentMethod description]
     *
     * @param   Package        $package                  [$package description]
     * @param   PaymentMethod  $paymentMethod            [$paymentMethod description]
     * @param   array          $paymentMethodAttributes  [$paymentMethodAttributes description]
     *
     * @return  [type]                                   [return description]
     */
    public function addPaymentMethod(Package $package, int $paymentMethodId, array $paymentMethodAttributes)
    {
        if ($package->paymentMethods()->where('payment_method_id', $paymentMethodId)->first()) {
            throw new Exception(__('resources.packages.paymentMethodAlreadyAdded'));
        }

        $paymentMethodAttributes = [
            'processor' => $paymentMethodAttributes['processor'],
            'tax' => parseNumber($paymentMethodAttributes['tax']),
            'discount' => parseNumber($paymentMethodAttributes['discount']),
            'limiter' => $paymentMethodAttributes['limiter'],
            'max_installments' => $paymentMethodAttributes['max_installments'],
            'first_installment_billet' => $paymentMethodAttributes['first_installment_billet'],
            'is_active' => true,
        ];

        $package->paymentMethods()->attach($paymentMethodId, $paymentMethodAttributes);
    }
}