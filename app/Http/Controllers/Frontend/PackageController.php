<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\DisplayType;
use App\Exceptions\NoStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\PackageSearchRequest;
use App\Models\Package;
use App\Repositories\BookingRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\EventRepository;
use App\Repositories\OfferRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PromocodeRepository;
use Exception;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * @var PackageRepository
     */
    protected $repository;

    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var OfferRepository
     */
    protected $offerRepository;

    /**
     * @var BookingRepository
     */
    protected $bookingRepository;

    /**
     * @var PromocodeRepository
     */
    protected $promocodeRepository;

    /**
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    public function __construct(
        PackageRepository $repository,
        EventRepository $eventRepository,
        CountryRepository $countryRepository,
        OfferRepository $offerRepository,
        BookingRepository $bookingRepository,
        PromocodeRepository $promocodeRepository,
        CurrencyRepository $currencyRepository)
    {
        $this->repository = $repository;
        $this->eventRepository = $eventRepository;
        $this->countryRepository = $countryRepository;
        $this->offerRepository = $offerRepository;
        $this->bookingRepository = $bookingRepository;
        $this->promocodeRepository = $promocodeRepository;
        $this->currencyRepository = $currencyRepository;

        $this->middleware('auth:clients')->only('book');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = $this->repository->list();

        return view('frontend.packages.index')
            ->with('packages', $packages);
    }

    /**
     * [show description]
     *
     * @param   Package  $package
     * @param   string   $slug
     *
     * @return  \Illuminate\Http\Response
     */
    public function show(Package $package, string $slug)
    {
        if($package->isNonListed()){
            return redirect()->route('frontend.index')->withError(__('resources.packages.is_non_listed'));
        }
        $this->repository->visit($package);
        clearPromocode();

        return view('frontend.packages.show')
            ->with('package', $package);
    }

    /**
     * [token description]
     *
     * @param   Package  $package
     * @param   string   $slug
     *
     * @return  \Illuminate\Http\Response
     */
    public function token(string $token)
    {
        $package = Package::where("token", $token)->first();

        if(!$package){
            return redirect()->route('frontend.index')->withError(__('resources.packages.is_non_listed'));
        }
        $package->setDisplayType(DisplayType::PUBLIC);
        return $this->show($package, $package->event->slug);
    }

    /**
     * [store description]
     *
     * @param   Request  $request  [$request description]
     * @param   Package  $package  [$package description]
     * @param   string   $slug     [$slug description]
     *
     * @return  [type]             [return description]
     */
    public function book(Request $request, Package $package, string $slug)
    {
        try {
            $attributes     = $request->all();
            $promocode      = session()->get('promocode');

            $package        = $this->repository->find($attributes['pid']);
            $passengers     = $attributes['numpass'];
            $offer          = $this->offerRepository->find($attributes['servprin']);
            $additionalIds  = $attributes['adicionais'] ?? [];
            if(!isset($attributes['promocode']) && $promocode != null){
                $attributes['promocode'] = $promocode->code;
            }
            $promocode      = $this->promocodeRepository->findByCode($attributes['promocode']);
            $currency       = $this->currencyRepository->findByCode($attributes['currency']);
            $selectedDates  = $attributes['servprindatas'] ?? null;
            $ip = ip();
            $clientId = auth('clients')->user()->id;
            if ($offer->isLongtrip()) {
                $longtripBoardingLocation       = $this->offerRepository->getLongtripBoardingLocation($attributes['servprinproduto2']);
                $longtripAccommodationPricing   = $this->offerRepository->getLongtripAccommodationsPricing($offer, $longtripBoardingLocation, $attributes['servprinproduto']);
                $booking = $this->bookingRepository->makeLongtripBooking($package, $offer, $currency, $longtripAccommodationPricing, $longtripBoardingLocation, $passengers, $clientId, $ip, $additionalIds, $promocode);
            } else {
                $product = $this->offerRepository->getProduct($offer, $attributes['servprinproduto']);
                $booking = $this->bookingRepository->makeBooking($package, $offer, $currency, $product, $passengers, $clientId, $ip, $additionalIds, $selectedDates, $promocode);
            }

            session()->put('booking', $booking);
            if($this->bookingRepository->getWarningNoStockException()){
                throw new NoStockException();
            }

            return redirect()->route(getRouteByLanguage('frontend.booking.summary'));
        } catch (NoStockException $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route(getRouteByLanguage('frontend.booking.summary'))->withError(__('resources.bookings.no-stock'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
        
    }
    /**
     * [search description]
     *
     * @param   PackageSearchRequest  $request  [$request description]
     *
     * @return  [type]                          [return description]
     */
    public function search(PackageSearchRequest $request)
    {
        try {
            $query = $request->get('q');

            $packages       = $this->repository->search($query);
            $events         = $this->eventRepository->search($query);
            $nextPackages   = $this->repository->listNextPackages();
            $otherEvents    = $this->eventRepository->listOnPrebooking(10);

            return view('frontend.packages.results')
                ->with('query', $query)
                ->with('packages', $packages)
                ->with('events', $events)
                ->with('nextPackages', $nextPackages)
                ->with('otherEvents', $otherEvents);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }
}
