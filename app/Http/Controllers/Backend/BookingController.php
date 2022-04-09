<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Bookings\BookingAdditionalStoreRequest;
use App\Http\Requests\Backend\Bookings\BookingVouchersRequest;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Models\BookingLog;
use App\Models\BookingPassenger;
use App\Models\BookingPassengerAdditional;
use App\Models\BookingProduct;
use App\Models\BookingVoucher;
use App\Models\BookingVoucherFile;
use App\Models\Company;
use App\Models\PaymentMethod;
use App\Models\Provider;
use App\Repositories\BookingBillRepository;
use App\Repositories\BookingLogRepository;
use App\Repositories\BookingPassengerAdditionalRepository;
use App\Repositories\BookingPassengerRepository;
use App\Repositories\BookingProductRepository;
use App\Repositories\BookingRepository;
use App\Repositories\BookingVoucherFileRepository;
use App\Repositories\BookingVoucherRepository;
use App\Repositories\ClientRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\OfferRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\ProviderRepository;
use App\Repositories\StateRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * @var BookingPassengerRepository
     */
    protected $bookingPassengerRepository;

    /**
     * @var BookingPassengerAdditionalRepository
     */
    protected $bookingPassengerAdditionalRepository;

    /**
     * @var BookingProductRepository
     */
    protected $bookingProductRepository;

    /**
     * @var BookingBillRepository
     */
    protected $bookingBillRepository;

    /**
     * @var BookingVoucherRepository
     */
    protected $bookingVoucherRepository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var PackageRepository
     */
    protected $packageRepository;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepository;

    /**
     * @var StateRepository
     */
    protected $stateRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    /**
     * @var OfferRepository
     */
    protected $offerRepository;

    /**
     * @var BookingLogRepository
     */
    protected $bookingLogRepository;

    public function __construct(
        BookingRepository $bookingRepository,
        OfferRepository $offerRepository,
        ClientRepository $clientRepository,
        BookingPassengerRepository $bookingPassengerRepository,
        BookingPassengerAdditionalRepository $bookingPassengerAdditionalRepository,
        BookingProductRepository $bookingProductRepository,
        BookingBillRepository $bookingBillRepository,
        BookingVoucherRepository $bookingVoucherRepository,
        CountryRepository $countryRepository,
        CompanyRepository $companyRepository,
        PackageRepository $packageRepository,
        PaymentMethodRepository $paymentMethodRepository,
        ProviderRepository $providerRepository,
        CurrencyRepository $currencyRepository,
        StateRepository $stateRepository,
        BookingLogRepository $bookingLogRepository,
        BookingVoucherFileRepository $bookingVoucherFileRepository,
        Request $req
        )
    {
        $this->bookingRepository = $bookingRepository;
        $this->bookingPassengerRepository   = $bookingPassengerRepository;
        $this->bookingPassengerAdditionalRepository = $bookingPassengerAdditionalRepository;
        $this->bookingProductRepository     = $bookingProductRepository;
        $this->bookingVoucherRepository     = $bookingVoucherRepository;
        $this->bookingBillRepository        = $bookingBillRepository;
        $this->countryRepository            = $countryRepository;
        $this->packageRepository            = $packageRepository;
        $this->paymentMethodRepository      = $paymentMethodRepository;
        $this->stateRepository              = $stateRepository;
        $this->clientRepository             = $clientRepository;
        $this->currencyRepository           = $currencyRepository;
        $this->offerRepository              = $offerRepository;
        $this->bookingLogRepository         = $bookingLogRepository;
        $this->bookingVoucherFileRepository = $bookingVoucherFileRepository;
        $this->companyRepository            = $companyRepository;
        $this->providerRepository           = $providerRepository;
        parent::__construct($req);
        $this->per_page=100;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $providers          = $this->providerRepository->setActor(user())->list();
            $companies          = $this->companyRepository->setActor(user())->list();
            $bookings           = $this->bookingRepository->list($this->per_page, $this->_filter_params);
            return view('backend.bookings.index')
                ->with('bookings', $bookings)
                ->with('companies', $companies)
                ->with('providers', $providers)
                ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $packages = $this->packageRepository->listActive();
            $clients = $this->clientRepository->listPerson();
            $currencies = $this->currencyRepository->list();

            $package_id = $request->input('package_id');
            $offer_id = $request->input('offer_id');
            $product_id = $request->input('product_id');

            $selectedPackage = null;
            $offers = null;
            $selectedOffer = null;
            $offerProducts = [];
            $selectedProduct = null;
            $mustSelectDates = false;

            if (!empty($package_id)) {
                $selectedPackage = $this->packageRepository->find($package_id);
                $offers = $this->offerRepository->findByPackageId($selectedPackage->id);
            }

            if (!empty($offer_id)) {
                $selectedOffer = $this->offerRepository->find($offer_id);

                if ($selectedOffer->isHotel()) {
                    $offerProducts = $this->offerRepository->getGroupedProducts($selectedOffer);
                    $mustSelectDates = true;
                } else {
                    $offerProducts = $this->offerRepository->getProducts($selectedOffer);
                }
            }

            if (!empty($product_id)) {
                $selectedProduct = $this->offerRepository->getProduct($selectedOffer, $product_id);
            }

            return view('backend.bookings.create')
                ->with('packages', $packages)
                ->with('clients', $clients)
                ->with('currencies', $currencies)
                ->with('offers', $offers)
                ->with('selectedPackage', $selectedPackage)
                ->with('selectedOffer', $selectedOffer)
                ->with('selectedProduct', $selectedProduct)
                ->with('offerProducts', $offerProducts)
                ->with('mustSelectDates', $mustSelectDates);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.index')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            $booking = $this->bookingRepository->basicStore($attributes);

            return redirect()->route('backend.bookings.edit', $booking)->withSuccess(__('resources.bookings.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Booking $booking)
    {
        if(!$this->checkManageBooking($booking)){
            return $this->redirectIndex($booking);
        }
        $navigation                            = $request->get('navigation', '');
        $countries                      = $this->countryRepository->list();
        $states                         = $this->stateRepository->getByCountryCode('BR');
        $offers                         = $this->packageRepository->getOffers($booking->package);
        $additionals                    = $this->packageRepository->getAdditionals($booking->offer);
        $paymentMethods['national']     = $this->paymentMethodRepository->getNationals();
        $paymentMethods['international'] = $this->paymentMethodRepository->getInternationals();

        $bookingLogs = $this->getLogs($booking);

        try {
            return view('backend.bookings.edit')
                ->with('booking', $booking)
                ->with('states', $states)
                ->with('offers', $offers)
                ->with('additionals', $additionals)
                ->with('paymentMethods', $paymentMethods)
                ->with('countries', $countries)
                ->with('navigation', $navigation)
                ->with('bookingLogs', $bookingLogs);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        try {
            if(!$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }

            $attributes = $request->all();

            $bookingAttributes = isset($attributes['booking']) ? $attributes['booking'] : [];
            $bookingClientAttributes = isset($attributes['bookingClient']) ? $attributes['bookingClient'] : [];
            $bookingProductsAttributes = isset($attributes['bookingProducts']) ? $attributes['bookingProducts'] : [];
            $bookingPassengersAttributes = isset($attributes['bookingPassengers']) ? $attributes['bookingPassengers'] : [];
            $bookingPassengerAdditionalsAttributes = isset($attributes['bookingPassengerAdditionals']) ? $attributes['bookingPassengerAdditionals'] : [];
            $bookingBillsAttributes = isset($attributes['bookingBills']) ? $attributes['bookingBills'] : [];
            $bookingVouchersAttributes = isset($attributes['bookingVoucher']) ? $attributes['bookingVoucher'] : [];
            $bookingVoucherFiles = isset($attributes['booking_voucher_files']) ? $attributes['booking_voucher_files'] : [];

            if (isset($bookingAttributes['expired_at'])) {
                $bookingAttributes['expired_at'] = convertDatetime($bookingAttributes['expired_at']);
            }

            if (isset($bookingClientAttributes['birthdate'])) {
                $bookingClientAttributes['birthdate'] = convertDate($bookingClientAttributes['birthdate']);
            }

            if (isset($bookingPassengersAttributes)) {
                foreach ($bookingPassengersAttributes as $key => $bookingPassengerAttributes) {
                    if (isset($bookingPassengerAttributes['birthdate'])) {
                        $bookingPassengersAttributes[$key]['birthdate'] = convertDate($bookingPassengerAttributes['birthdate']);
                    }
                }
            }

            if (isset($bookingVouchersAttributes)) {
                foreach ($bookingVouchersAttributes as $key => $bookingVoucherAttributes) {
                    if (isset($bookingVoucherAttributes['released_at'])) {
                        $bookingVouchersAttributes[$key]['released_at'] = convertDate($bookingVoucherAttributes['released_at']);
                    }
                }
            }

            if (isset($bookingBillsAttributes)) {
                foreach ($bookingBillsAttributes as $key => $bookingBillAttributes) {
                    if (isset($bookingBillAttributes['expires_at'])) {
                        $bookingBillsAttributes[$key]['expires_at'] = convertDate($bookingBillAttributes['expires_at']);
                    }
                }
            }

            $booking = $this->bookingRepository->completeUpdate(
                $booking,
                $bookingAttributes,
                $bookingClientAttributes,
                $bookingProductsAttributes,
                $bookingPassengersAttributes,
                $bookingBillsAttributes,
                $bookingPassengerAdditionalsAttributes,
                $bookingVoucherFiles,
                $bookingVouchersAttributes
            );

            $navigation = $attributes['navigation'];

            return redirect()->route('backend.bookings.edit', ["booking" => $booking, "navigation"  => $navigation])->withSuccess(__('resources.bookings.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        try {
            if(!$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }

            $this->bookingRepository->delete($booking);

            return redirect()->route('backend.bookings.index')->withSuccess(__('resources.bookings.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.index')->withError($ex->getMessage());
        }
    }

    /**
     * Filter the specified resource from storage
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        try {
            $providers          = $this->providerRepository->setActor(user())->list();
            $companies          = $this->companyRepository->setActor(user())->list();
            $this->_params = $request->except(['_token']);

            $bookings   = $this->bookingRepository->filter($this->_params, $this->per_page);
            return view('backend.bookings.index')
                ->with('bookings', $bookings)
                ->with('companies', $companies)
                ->with('providers', $providers)
                ->with('_params', $this->_params);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.index')->withError($ex->getMessage());
        }
    }

    /**
     * [cancel description]
     *
     * @param   Booking  $id  [$id description]
     *
     * @return  [type]        [return description]
     */
    public function cancel(Booking $booking)
    {
        try {
            if(!$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }

            $this->bookingRepository->cancel($booking);

            return redirect()->route('backend.bookings.edit', $booking)->withSuccess(__('resources.bookings.canceled'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [createPassenger description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function createPassenger(Booking $booking)
    {
        try {
            if(!$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }

            $states = $this->stateRepository->getByCountryCode('BR');

            return view('backend.bookings.passengers.create')
                ->with('booking', $booking)
                ->with('states', $states);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [createPassenger description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function storePassenger(Request $request, Booking $booking)
    {
        try {
            if(!$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }

            $attributes = $request->all();

            $attributes['birthdate'] = convertDate($attributes['birthdate']);

            $this->bookingPassengerRepository->setBooking($booking)->store($attributes);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'passengers'])->withSuccess(__('resources.booking-passengers.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.storePassenger', ['booking' => $booking, 'navigation' => 'passengers'])->withInput()->withError($ex->getMessage());
        }
    }

    /**
     * [createPassenger description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function destroyPassenger(Request $request, Booking $booking, BookingPassenger $bookingPassenger)
    {
        try {
            if(!$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }

            $this->bookingPassengerRepository->delete($bookingPassenger);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'passengers'])->withSuccess(__('resources.booking-passengers.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'passengers'])->withError($ex->getMessage());
        }
    }

    /**
     * [createProduct description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function createProduct(Request $request, Booking $booking)
    {
        try {
            if(!user()->isMaster() || !$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }
            $companyId         = $request->get("company_id", -1);
            $providers          = $this->packageRepository->getProvidersByPackage($booking->package);
            $companies          = $this->packageRepository->getCompaniesByPackage($booking->package);
            if($companyId<=0){
                $companyId     = $companies->first()->id;
            }
            return view('backend.bookings.products.create')
                ->with('booking', $booking)
                ->with('providers', $providers)
                ->with('companies', $companies)
                ->with('companyId', $companyId);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [createAdditional description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function storeProduct(Request $request, Booking $booking)
    {
        try {
            if(!user()->isMaster() || !$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }

            $attributes = $request->all();
            $currencyOffer                      = $booking->offer->getCurrency();
            $_bookingAttributes = [
                "currency_id" => $booking->currency_id,
                "currency_origin_id" => ($currencyOffer)?$currencyOffer->id:null,
                "product_type" => $booking->product_type,
            ];
            $attributes['price']    = str_replace(",",".", str_replace(".","", $attributes['price']));
            if (isset($attributes['date'])) {
                $attributes['date'] = convertDate($attributes['date']);
            }
            $bookingProduct = new BookingProduct();
            $bookingProduct->fill($attributes);
            $bookingProduct->fill($_bookingAttributes);

            $saleCoefficient    = $bookingProduct->getProduct()->getSaleCoefficient();
            $priceNet           = ($attributes['price'] / $saleCoefficient);

            $bookingProduct->fill($_bookingAttributes = [
                "sale_coefficient" => $saleCoefficient,
                "price_net" => $priceNet,
            ]);

            $bookingProducts    = $this->bookingRepository->storeBookingProducts($booking, [$bookingProduct]);

            //$this->bookingRepository->setBooking($booking)->store($attributes);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'products'])->withSuccess(__('resources.booking-products.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'products'])->withError($ex->getMessage());
        }
    }

    /**
     * [destroyProduct description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function destroyProduct(Request $request, Booking $booking, BookingProduct $bookingProduct)
    {
        try {
            if(!user()->isMaster()){
                return $this->redirectIndex($booking);
            }

            $this->bookingRepository->destroyBookingProduct($booking, $bookingProduct);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'products'])->withSuccess(__('resources.booking-products.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'products'])->withError($ex->getMessage());
        }
    }

    /**
     * [createAdditional description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function createAdditional(Request $request, Booking $booking)
    {
        try {
            if(!$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }

            $companyId         = $request->get("company_id", -1);
            $providers          = $this->packageRepository->getProvidersByPackage($booking->package);
            $companies          = $this->packageRepository->getCompaniesByPackage($booking->package);
            if($companyId<=0){
                $companyId     = $companies->first()->id;
            }
            $company            = app(Company::class)->find($companyId);

            $additionals = $this->packageRepository->getAdditionalByCompany($booking->offer, $company);

            return view('backend.bookings.additionals.create')
                ->with('booking', $booking)
                ->with('providers', $providers)
                ->with('companies', $companies)
                ->with('companyId', $companyId)
                ->with('additionals', $additionals);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [createAdditional description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function storeAdditional(BookingAdditionalStoreRequest $request, Booking $booking)
    {
        try {
            if(!$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }

            $attributes = $request->all();

            $this->bookingPassengerAdditionalRepository->setBooking($booking)->store($attributes);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'products'])->withSuccess(__('resources.booking-passenger-additionals.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'products'])->withError($ex->getMessage());
        }
    }

    /**
     * [createAdditional description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function destroyAdditional(Request $request, Booking $booking, BookingPassengerAdditional $bookingPassengerAdditional)
    {
        try {
            $this->bookingPassengerAdditionalRepository->delete($bookingPassengerAdditional);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'products'])->withSuccess(__('resources.booking-passenger-additionals.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'products'])->withError($ex->getMessage());
        }
    }

    /**
     * [createVoucher description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function createVoucher(Booking $booking)
    {
        try {
            $inclusions = $this->bookingRepository->getInclusions($booking);
            $observations = $this->bookingRepository->getObservations($booking);

            return view('backend.bookings.vouchers.create')
                ->with('booking', $booking)
                ->with('inclusions', $inclusions)
                ->with('observations', $observations);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'vouchers'])->withError($ex->getMessage());
        }
    }

    /**
     * [createVoucher description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function storeVoucher(BookingVouchersRequest $request, Booking $booking)
    {
        try {
            if(!$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }
            $attributes = $request->all();

            $attributes['released_at'] = convertDate($attributes['released_at']);

            $this->bookingVoucherRepository->setBooking($booking)->store($attributes);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'vouchers'])->withSuccess(__('resources.booking-vouchers.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'vouchers'])->withError($ex->getMessage());
        }
    }

    /**
     * [createVoucher description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function destroyVoucher(Request $request, Booking $booking, BookingVoucher $bookingVoucher)
    {
        try {
            $this->bookingVoucherRepository->delete($bookingVoucher);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'vouchers'])->withSuccess(__('resources.booking-vouchers.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'vouchers'])->withError($ex->getMessage());
        }
    }

    /**
     * [viewVoucher description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function viewVoucher(Booking $booking, BookingVoucher $bookingVoucher)
    {
        try {
            if(!$this->checkManageBooking($booking)){
                return $this->redirectIndex($booking);
            }
            $voucher = view(getViewByLanguage('frontend.my-account.bookings.vouchers.voucher', "_"))
                ->with('booking'    , $booking)
                ->with('voucher'    , $bookingVoucher)
                ->with('provider'   , $booking->offer->provider)
                ->render();

            return $voucher;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [destroyVoucherFile description]
     *
     * @param   Request             $request             [$request description]
     * @param   Booking             $booking             [$booking description]
     * @param   BookingVoucherFile  $bookingVoucherFile  [$bookingVoucherFile description]
     *
     * @return  [type]                                   [return description]
     */
    public function destroyVoucherFile(Request $request, Booking $booking, BookingVoucherFile $bookingVoucherFile)
    {
        try {
            $this->bookingVoucherFileRepository->delete($bookingVoucherFile);

            return redirect()->route('backend.bookings.edit', $booking)->withSuccess(__('resources.booking-voucher-files.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [createBill description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function createBill(Booking $booking)
    {
        try {
            // Rule: when adding a bill, add directly and then redirect to booking edit
            $attributes['total']                = 0;
            $attributes['payment_method_id']    = (app(PaymentMethod::class)->first())->id;
            $attributes['installment']          = $booking->bookingBills()->count()+1;
            $this->bookingBillRepository->setBooking($booking)->store($attributes);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'payment'])->withSuccess(__('resources.booking-bills.created'));

            $paymentMethods['national'] = $this->paymentMethodRepository->setPackage($booking->package)->getNationals();
            $paymentMethods['international'] = $this->paymentMethodRepository->setPackage($booking->package)->getInternationals();
            $nextInstallment = $this->bookingBillRepository->getBookingNextInstallment($booking);
            $nextCt = $this->bookingBillRepository->getBookingNextCt($booking);

            return view('backend.bookings.bills.create')
                ->with('booking', $booking)
                ->with('paymentMethods', $paymentMethods)
                ->with('nextInstallment', $nextInstallment)
                ->with('nextCt', $nextCt);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'payment'])->withError($ex->getMessage());
        }
    }

    /**
     * [storeBill description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function storeBill(Request $request, Booking $booking)
    {
        try {
            $attributes = $request->all();

            $attributes['expires_at'] = convertDate($attributes['expires_at']);

            $this->bookingBillRepository->setBooking($booking)->store($attributes);

            return redirect()->route('backend.bookings.edit', $booking)->withSuccess(__('resources.booking-bills.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [destroyBill description]
     *
     * @param   Booking      $booking      [$booking description]
     * @param   BookingBill  $bookingBill  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function destroyBill(Request $request, Booking $booking, BookingBill $bookingBill)
    {
        try {
            $this->bookingBillRepository->delete($bookingBill);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'payment'])->withSuccess(__('resources.booking-bills.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'payment'])->withError($ex->getMessage());
        }
    }

    /**
     * [cancelBill description]
     *
     * @param   Booking      $booking      [$booking description]
     * @param   BookingBill  $bookingBill  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function cancelBill(Request $request, Booking $booking, BookingBill $bookingBill)
    {
        try {
            $this->bookingBillRepository->cancel($bookingBill);

            return redirect()->route('backend.bookings.edit', $booking)->withSuccess(__('resources.booking-bills.canceled'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [payBill description]
     *
     * @param   Booking      $booking      [$booking description]
     * @param   BookingBill  $bookingBill  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function payBill(Request $request, Booking $booking, BookingBill $bookingBill)
    {
        try {
            $this->bookingBillRepository->pay($bookingBill);

            return redirect()->route('backend.bookings.edit', $booking)->withSuccess(__('resources.bookings.bills.paid'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [restoreBill description]
     *
     * @param   Booking      $booking      [$booking description]
     * @param   BookingBill  $bookingBill  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function restoreBill(Request $request, Booking $booking, BookingBill $bookingBill)
    {
        try {
            $this->bookingBillRepository->restore($bookingBill);

            return redirect()->route('backend.bookings.edit', $booking)->withSuccess(__('resources.booking-bills.restored'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [generateBill description]
     *
     * @param   Booking      $booking      [$booking description]
     * @param   BookingBill  $bookingBill  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function generateBill(Request $request, Booking $booking)
    {
        try {
            $this->bookingBillRepository->generate($booking);

            return redirect()->route('backend.bookings.edit', $booking)->withSuccess(__('resources.booking-bills.generated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [reportExport description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function reportExport(Request $request)
    {
        return redirect()->route('backend.reports.reports.bookings.index');
    }

    /**
     * [getLogs description]
     *
     * @param   Client  $client  [$client description]
     *
     * @return  [type]           [return description]
     */
    public function getLogs(Booking $booking)
    {
        return $this->bookingLogRepository->getByTargetBooking(
            $booking,
            auth('users')->user() ?? auth('providers')->user()
        );
    }

    /**
     * [storeLog description]
     *
     * @param   Request  $request  [$request description]
     * @param   Booking  $booking   [$client description]
     *
     * @return  [type]             [return description]
     */
    public function storeLog(Request $request, Booking $booking)
    {
        try {
            $attributes = $request->all();

            $provider = auth('providers')->user();
            $user = auth('users')->user();

            $this->bookingLogRepository
                ->setTargetBooking($booking)
                ->setTargetClient($booking->client)
                ->setProvider($provider)
                ->setUser($user)
                ->store([
                    'message' => $attributes['log']['message'],
                    'level' => $attributes['log']['level'],
                    'ip' => ip(),
                    'type' => 'manual',
                ]);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'logs'])->withSuccess(__('resources.logs.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'logs'])->withError($ex->getMessage());
        }
    }

    /**
     * [destroyLog description]
     *
     * @param   Request  $request  [$request description]
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function destroyLog(Request $request, Booking $booking)
    {
        $this->authorize('delete', BookingLog::class);

        try {
            $attributes = $request->all();

            $this->bookingLogRepository->deleteMany($attributes['deleteLogs']);

            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'logs'])->withSuccess(__('resources.logs.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', ['booking' => $booking, 'navigation' => 'logs'])->withError($ex->getMessage());
        }
    }

    /**
     * [contract description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function contract(Booking $booking)
    {
        if (!user()->canSeeBookingContract()) {
            return $this->redirectIndex($booking);
        }

        try {
            $contract = view('frontend.booking.contract')
                ->with('mustPreRenderPhone', true)
                ->with('showPaymentDetails', true)
                ->with('booking', $booking)
                ->render();

            return view('backend.bookings.contract')
                ->with('contract', $contract);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [confirmation description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function confirmation(Booking $booking)
    {
        if (!user()->canSeeBookingConfirmation()) {
            return $this->redirectIndex($booking);
        }

        try {
            return view('backend.bookings.confirmation')
                ->with('booking', $booking);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }

    /**
     * [checkManageBooking description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function checkManageBooking(Booking $booking)
    {
        $result = user()->can('manage', $booking);
        if(!$result){
            // Cannot see this booking
            redirect()->route('backend.bookings.index')->withError(__('messages.no_access'));
            return false;
        }
        if(!user()->isMaster() && $booking->isCanceled()){
            return false;
        }
        return true;

    }

    /**
     * [sendBookingNotification description]
     *
     * @param   Request  $request  [$request description]
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function sendBookingNotification(Request $request, Booking $booking)
    {
        $result = user()->can('manage', $booking);
        $_data  = $request->all();
        try{
            $this->bookingRepository->sendBookingNotification($booking, $_data);
            return redirect()->route('backend.bookings.edit', $booking)->withSuccess(__('resources.bookings.notification-sent'));
        }catch(Exception $ex){
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());

        }


    }

    private function redirectIndex(Booking $booking){
        return redirect()->route('backend.bookings.index')->withError(__('messages.no_access'));
    }

}
