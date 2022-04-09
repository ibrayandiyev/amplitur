<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\OfferType;
use App\Exceptions\NoStockException;
use App\Exceptions\Promocodes\PromocodeException;
use App\Http\Controllers\Controller;
use App\Models\LongtripAccommodationType;
use App\Models\LongtripBoardingLocation;
use App\Models\Offer;
use App\Repositories\LongtripAccommodationRepository;
use App\Repositories\LongtripRouteRepository;
use App\Repositories\OfferRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\PromocodeRepository;
use Exception;
use Illuminate\Http\Request;

class PackageShoppingController extends Controller
{
    /**
     * @var PackageRepository
     */
    protected $repository;

    /**
     * @var OfferRepository
     */
    protected $offerRepository;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepository;

    /**
     * @var PromocodeRepository
     */
    protected $promocodeRepository;

    public function __construct(
        PackageRepository $repository,
        OfferRepository $offerRepository,
        PaymentMethodRepository $paymentMethodRepository,
        PromocodeRepository $promocodeRepository)
    {
        $this->repository = $repository;
        $this->offerRepository = $offerRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->promocodeRepository = $promocodeRepository;
    }

    /**
     * [getOffer description]
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function getOffer(Request $request)
    {
        $attributes = $request->toArray();

        $offer = Offer::where('type', $attributes['grupo'])->first();

        return response()->json($attributes);
    }

    /**
     * [getOffer description]
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function getOfferAdditionals(Request $request)
    {
        $attributes = $request->toArray();

        $offer = $this->offerRepository->find($attributes['servprin']);
        $onlyActiveOffers = true;
        $additionalGroups = $this->offerRepository->getGroupedProductAdditionals($offer, $attributes['product'], $attributes['selectedDates'] ?? null, $onlyActiveOffers);
        $passengers = $attributes['numpass'];

        if (is_null($additionalGroups) || $additionalGroups->count() == 0) {
            return response('', 200);
        }

        return view('frontend.packages.partials.product-additionals')
            ->with('passengers', $passengers)
            ->with('offer', $offer)
            ->with('additionalGroups', $additionalGroups);
    }

    /**
     * [getOffer description]
     * getservprinajax
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function getOfferProduct(Request $request)
    {
        $attributes = $request->toArray();

        $package = $this->repository->find($attributes['pacote']);

        if ($attributes['grupo'] == 'hotel') {
            $offer = $this->offerRepository->find($attributes['servprin']);

            $products = $this->offerRepository->getGroupedProducts($offer);

            return view('frontend.packages.partials.package-bookable-products')
                ->with('products', $products)
                ->with('package', $package)
                ->with('offer', $offer);
        }

        if ($attributes['grupo'] == 'longtrip') {
            /**
             * Attribute auxvar1 = longtripRoute
             * Attribute product = longtripBoardingLocation
             */
            $longtripRouteId                = $attributes['auxvar1'];
            $longtripBoardingLocation       = $attributes['product'];
            $offer                          = $this->offerRepository->find($attributes['servprin']);
            $longtripRoute                  = $offer->longtripRoutes()->where("id", $longtripRouteId)->first();
            $longtripBoardingLocation       = (new LongtripBoardingLocation())->find($longtripBoardingLocation);
            
            return view('frontend.packages.partials.package-longtrip-products')
                ->with('longtripRoute', $longtripRoute)
                ->with('package', $package)
                ->with('longtripBoardingLocation', $longtripBoardingLocation)
                ->with('offer', $offer)
                ->with('numpass', $attributes['numpass'])
                ;
        }

        $product = $this->offerRepository->getOfferProduct($attributes['grupo'], $attributes['product']);

        return view('frontend.packages.partials.product-details')
            ->with('package', $package)
            ->with('product', $product);
    }

    /**
     * [getBookableOfferProducts description]
     * getformapagajax
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function getBookableOfferProduct(Request $request)
    {
        $attributes = $request->toArray();

        $package = $this->repository->find($attributes['packageId']);
        $product = $this->offerRepository->getOfferProduct($attributes['grupo'], $attributes['servprin']);

        $routePaymentDetails = getRouteByLanguage('frontend.ajax.payment-details');
        return view('frontend.packages.partials.product-details-bookable')
            ->with('package', $package)
            ->with('routePaymentDetails', $routePaymentDetails)
            ->with('product', $product);
    }

    public function getLongtripAccommodationDetails(Request $request)
    {
        /**
         * Attribute longtripBoardingLocation = longtripBoardingLocation
         * Attribute longtripRoute = longtripRoute
         * Attribute servprin = longtripAccommodationsPricing
         */
        $attributes                     = $request->toArray();
        $longtripBoardingLocation       = $attributes['servprin'];
        $longtripRoute                  = $attributes['longtripRoute'];
        $longtripBoardingLocation       = $attributes['longtripBoardingLocation'];
        $longtripAccomodationType       = $attributes['longtripAccomodationType'];

        $package                        = $this->repository->find($attributes['packageId']);
        $longtripRoute                  = app(LongtripRouteRepository::class)->find($longtripRoute);
        $longtripBoardingLocation       = (new LongtripBoardingLocation())->find($longtripBoardingLocation);
        $longtripAccomodationType      = (new LongtripAccommodationType())->find($longtripAccomodationType);
        $longtripAccomodation           = app(LongtripAccommodationRepository::class)->getLongtripAccommodationByRouteAccommodationType($longtripRoute->id, $longtripAccomodationType->id);

        return view('frontend.packages.partials.product-details-longtrip')
            ->with('package'                    , $package)
            ->with('product'                    , $longtripBoardingLocation)
            ->with('longtripRoute'              , $longtripRoute)
            ->with('longtripAccomodationType'   , $longtripAccomodationType)
            ->with('longtripAccomodation'       , $longtripAccomodation)
            ;
    }

    /**
     * [getOffer description]
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function getPaymentDetails(Request $request)
    {
        $promocode_discount = 0;
        $attributes = $request->toArray();

        $package        = $this->repository->find($attributes['pacote'] ?? 0);
        $offer          = $this->offerRepository->find($attributes['servprin'] ?? 0);
        if($offer->type == OfferType::LONGTRIP){
            $product[] = $this->offerRepository->getOfferProduct($offer->type, $attributes['servsec'] ?? 0);
            $product[] = $this->offerRepository->getOfferProduct(OfferType::LONGTRIP_BOARDING_LOCATION, $attributes['product'] ?? 0);
        }else{
            $product = $this->offerRepository->getOfferProduct($offer->type, $attributes['product'] ?? 0);
        }
        $additionalIds = $attributes['servadic'] ?? [];
        $dates = $attributes['selectedDates'] ?? [];
        $passengers = $attributes['numpass'] ?? 1;
        $promocode  = session()->get('promocode');
        if($promocode){
            $promocode_discount      = app(PromocodeRepository::class)->getDiscount($promocode, currency());
        }
        $total          = $this->offerRepository->getPaymentTotal($package, $offer, $additionalIds, $product, $dates, $passengers);
        $paymentMethods = $this->repository->getPackagePaymentMethods($package);
        $total          -= $promocode_discount;

        return view('frontend.packages.partials.payment-details')
            ->with('total', $total)
            ->with('paymentMethods', $paymentMethods);
    }

    /**
     * [applyPromoCode description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function applyPromoCode(Request $request)
    {
        $attributes = $request->toArray();

        try{
            $clientId       = null;
            if(auth('clients')->user()){
                $clientId   = auth('clients')->user()->id;
            }
            $promocode  = $this->promocodeRepository->validatePromocode($attributes['promocode'], $attributes['pacote'] ?? null);
            session()->put('promocode', $promocode);
        }catch(PromocodeException $promocodeException){
            return response()->json([
                'msg' => $promocodeException->getMessage(),
                'status' => "erro",
            ]);
        }

        return response()->json([
            'desconto' => $promocode->discount_value,
            'msg' => "Promocode aplicado! Você está economizando {$promocode->discount_value}",
            'status' => "sucesso",
        ]);
    }

    /**
     * [changePassengers description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function changePassengers(Request $request)
    {
        $attributes = $request->toArray();

        if (empty($attributes['servprin']) || empty($attributes['numpass']) || empty($attributes['product'])) {
            return;
        }

        return $this->getOfferAdditionals($request);
    }

    /**
     * [updateBooking description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function updateBooking(Request $request)
    {
        die("this will be the future pre-booking");
        try {
            $attributes = $request->all();

            $package    = $this->repository->find($attributes['pid']);
            $passengers = $attributes['numpass'];
            $offer      = $this->offerRepository->find($attributes['servprin']);
            $additionalIds = $attributes['adicionais'] ?? [];
            $promocode  = $this->promocodeRepository->findByCode($attributes['promocode']);
            $currency   = $this->currencyRepository->findByCode($attributes['currency']);
            $selectedDates = $attributes['servprindatas'] ?? null;
            $ip         = ip();
            if ($offer->isLongtrip()) {
                $longtripBoardingLocation       = $this->offerRepository->getLongtripBoardingLocation($attributes['servprinproduto2']);
                $longtripAccommodationPricing   = $this->offerRepository->getLongtripAccommodationsPricing($offer, $longtripBoardingLocation, $attributes['servprinproduto']);
                $booking = $this->bookingRepository->makeLongtripBooking($package, $offer, $currency, $longtripAccommodationPricing, $longtripBoardingLocation, $passengers, $clientId, $ip, $additionalIds, $promocode);
            } else {
                $product = $this->offerRepository->getProduct($offer, $attributes['servprinproduto']);
                $booking = $this->bookingRepository->makeBooking($package, $offer, $currency, $product, $passengers, $clientId, $ip, $additionalIds, $selectedDates, $promocode);
            }

            session()->put('booking', $booking);

            return $this->sendResponse();
        } catch (NoStockException $ex) {
            bugtracker()->notifyException($ex);
            return $this->sendError();
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return $this->sendError();
        }
    }
}
