<?php

namespace App\Repositories;

use App\Enums\Bookings\BookingNotifications;
use App\Enums\Bookings\BookingPayments;
use App\Enums\OfferType;
use App\Enums\ProcessStatus;
use App\Exceptions\NoStockException;
use App\Mail\Bookings\BookingNotificationMail;
use App\Models\Booking;
use App\Models\BookingClient;
use App\Models\BookingLegacies;
use App\Models\BookingOffer;
use App\Models\BookingPassenger;
use App\Models\BookingPassengerAdditional;
use App\Models\BookingProduct;
use App\Models\Client;
use App\Models\ClientPaymentDataset;
use App\Models\Currency;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Promocode;
use App\Repositories\Traits\Bookings\BookingAdditionalOperationsTrait;
use App\Repositories\Traits\Bookings\BookingRelations;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use Throwable;

class BookingRepository extends Repository
{
    use BookingRelations,
    BookingAdditionalOperationsTrait;

    /**
     * @var BookingPayments
     */
    protected $postponePayment = BookingPayments::POSTPONE_NONE;

    /**
     * @var BookingLogRepository
     */
    protected $logging;

    /**
     * Store information used in the booking reservation email
     * @var array
     */
    private $_emailData = [];

    public function __construct(Booking $model)
    {
        $this->model = $model;
        $this->modelBookingLegacies = new BookingLegacies();
        $this->logging = app(BookingLogRepository::class);
    }

    protected function onBeforeListFilter($model)
    {
        if(!user()->canManageProviders()){
            $user           = (user());
            $provider_id    = $user->id;
            $model          = $model->select(['bookings.*'])->join('offers', 'offers.id', '=', 'bookings.offer_id')
            ->where('offers.provider_id', '=', $provider_id);
        }
        $model = $model->orderBy('id', 'desc');
        
        return $model;
    }


    /**
     * [completeUpdate description]
     *
     * @param   array  $bookingAttributes            [$bookingAttributes description]
     * @param   array  $bookingClientAttributes      [$bookingClientAttributes description]
     * @param   array  $bookingProductsAttributes    [$bookingProductsAttributes description]
     * @param   array  $bookingPassengersAttributes  [$bookingPassengersAttributes description]
     * @param   array  $bookingBillsAttributes       [$bookingBillsAttributes description]
     * @param   array  $bookingPassengerAdditionalsAttributes       [$bookingPassengerAdditionalsAttributes description]
     *
     * @return  [type]                               [return description]
     */
    public function completeUpdate(
        Booking $booking,
        array $bookingAttributes,
        array $bookingClientAttributes,
        array $bookingProductsAttributes,
        array $bookingPassengersAttributes,
        array $bookingBillsAttributes,
        array $bookingPassengerAdditionalsAttributes,
        array $bookingVoucherFiles,
        array $bookingVouchersAttributes
    ) {
        try {
            DB::beginTransaction();

            if ($booking->isCanceled() && !user()->isMaster()) {
                $this->update($booking, [
                    'payment_status' => $bookingAttributes['payment_status']
                ]);

                DB::commit();

                return $booking->fresh();
            }

            $this->update($booking, $bookingAttributes);
            $this->updateBookingClient($booking->bookingClient, $bookingClientAttributes);
            $this->updateBookingBills($booking, $bookingBillsAttributes);
            $this->updateBookingProducts($booking, $bookingProductsAttributes);
            $this->updateBookingPassengers($booking, $bookingPassengersAttributes);
            $this->updateBookingPassengerAdditionals($booking, $bookingPassengerAdditionalsAttributes);
            $this->updateBookingVouchers($booking, $bookingVouchersAttributes);
            $this->uploadVoucherFiles($booking, $bookingVoucherFiles);
            $this->refreshBooking($booking);
            $this->recalculate($booking);
            $this->logging->bookingUpdated($booking);
            DB::commit();
            return $booking->fresh();
        } catch (Throwable $ex) {
            bugtracker()->notifyException($ex);
            DB::rollBack();
            throw $ex;
        }
    }

    public function basicStore(array $attributes): Booking
    {
        $booking = new Booking;
        $package = app(PackageRepository::class)->find($attributes['package_id']);
        $offer = app(OfferRepository::class)->find($attributes['offer_id']);
        $product =  app(OfferRepository::class)->getProduct($offer, $attributes['product_id']);
        $currency = app(CurrencyRepository::class)->find($attributes['currency_id']);
        $client = app(ClientRepository::class)->find($attributes['client_id']);
        $dates = $attributes['dates'] ?? null;

        if (empty($package) || empty($currency) || empty($client)) {
            throw new InvalidArgumentException;
        }

        try {
            DB::beginTransaction();

            $booking = $this->makeBooking($package, $offer, $currency, $product, 1, $client->id, null, [], $dates, null);

            $booking->expired_at = Carbon::now()->addDays($booking->package->payment_expire_days);
            $booking->status = ProcessStatus::CONFIRMED;

            $bookingPassengers  = $booking->bookingPassengers;
            $bookingClient      = $booking->bookingClient;
            $bookingOffer       = $booking->bookingOffer;
            $bookingProducts    = $booking->bookingProducts;

            $booking = $this->store($booking->toArray());

            $bookingClient = $this->storeBookingClient($booking, $bookingClient);
            $bookingOffer = $this->storeBookingOffer($booking, $bookingOffer);
            $bookingPassengers = $this->storeBookingPassengers($booking, $bookingPassengers);
            $bookingProducts = $this->storeBookingProducts($booking, $bookingProducts);

            $this->logging->bookingCreated($booking);

            DB::commit();

            return $booking->fresh();
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            DB::rollback();
            throw $ex;
        }
    }

    /**
     * Filter a resource
     *
     * @param   array  $id
     *
     * @return  Model|Collection|null
     */
    public function filter(array $params, ?int $limit = null): SupportCollection
    {
        $builder = $this->model->query(); 

        if(isset($params["search_for"]) && isset($params["wildcard"]) && $params["wildcard"] != ""){
            $params[$params["search_for"]] = $params["wildcard"];
            unset($params["search_for"]);
            unset($params["wildcard"]);
        }
        if(isset($params["booking_offer_company_id"][0]) && !is_numeric($params["booking_offer_company_id"][0])){
            unset($params["booking_offer_company_id"]);
        }
        if(isset($params["booking_offer_provider_id"][0]) && !is_numeric($params["booking_offer_provider_id"][0])){
            unset($params["booking_offer_provider_id"]);
        }
        if(isset($params["id"])){
            $id = $params["id"];
            unset($params["id"]);
            $params["id"][] = $id;
        }
        return parent::filter($params, $limit);
    }

    public function processTotals($booking, $package, $offer, $additionalIds, $product, $selectedDates, $passengers, $promocode){
        
        $booking->subtotal      = app(OfferRepository::class)->getPaymentTotal($package, $offer, collect($additionalIds)->flatten()->toArray(), $product, $selectedDates, $passengers);
        $booking->discount      = app(PromocodeRepository::class)->getDiscount($promocode, $booking->currency);
        $booking->total         = $this->getBookingTotal($booking);
        return $booking;
    }

    /**
     * [makeBooking description]
     *
     * @param   Package    $package        [$package description]
     * @param   Offer      $offer          [$offer description]
     * @param   Currency   $currency       [$currency description]
     * @param   object     $product        [$product description]
     * @param   int        $passengers     [$passengers description]
     * @param   string     $ip             [$ip description]
     * @param   array      $additionalIds  [$additionalIds description]
     * @param   array      $selectedDates  [$selectedDates description]
     * @param   Promocode  $promocode      [$promocode description]
     *
     * @return  Booking                    [return description]
     */
    public function makeBooking(Package $package, Offer $offer, Currency $currency, object $product, int $passengers, $clientId, ?string $ip, ?array $additionalIds, ?array $selectedDates, ?Promocode $promocode): Booking
    {
        $booking = new Booking;

        $booking->package_id    = $package->id;
        $booking->offer_id      = $offer->id;
        $booking->product_id    = $product->id;
        $booking->product_type  = get_class($product);
        $booking->product_dates = is_array($selectedDates) && !empty($selectedDates) ? json_encode($selectedDates) : null;
        $booking->promocode_id  = $promocode->id ?? null;
        $booking->currency_id   = $currency->id;
        $booking->client_id     = $clientId;
        $booking->passengers    = $passengers;
        $booking->starts_at     = $this->getStartDate($product, $selectedDates);
        $booking                = $this->processTotals($booking, $package, $offer, $additionalIds, $product, $selectedDates, $passengers, $promocode);
        $booking->quotations    = json_encode(app(CurrencyQuotationRepository::class)->list()->toArray());
        $booking->ip            = $ip;

        $additionalIds          = $this->parseAdditionalIds($additionalIds);

        $booking->bookingClient = $this->makeBookingClient($booking);
        $booking->bookingPassengers = $this->makeBookingPassengers($booking, $additionalIds);
        $booking->bookingOffer = $this->makeBookingOffer($booking, $package, $offer, $product, $selectedDates);
        $booking->bookingProducts = $this->makeBookingProducts($booking, $offer, $currency, $product, $selectedDates);
        $booking->product = $product;
        
        $this->checkPassengersAdditionalStock($booking);
        $booking                = $this->processTotals($booking, $package, $offer, $additionalIds, $product, $selectedDates, $passengers, $promocode);

        return $booking;
    }

    /**
     * [makeLongtripBooking description]
     *
     * @param   Package    $package                       [$package description]
     * @param   Offer      $offer                         [$offer description]
     * @param   Currency   $currency                      [$currency description]
     * @param   object     $longtripAccommodationPricing  [$longtripAccommodationPricing description]
     * @param   object     $longtripBoardingLocation      [$longtripBoardingLocation description]
     * @param   int        $passengers                    [$passengers description]
     * @param   [type]     $clientId                      [$clientId description]
     * @param   string     $ip                            [$ip description]
     * @param   array      $additionalIds                 [$additionalIds description]
     * @param   Promocode  $promocode                     [$promocode description]
     *
     * @return  Booking                                   [return description]
     */
    public function makeLongtripBooking(Package $package, Offer $offer, Currency $currency, object $longtripAccommodationPricing, object $longtripBoardingLocation, int $passengers, $clientId, ?string $ip, ?array $additionalIds, ?Promocode $promocode): Booking
    {
        $booking = new Booking;

        $booking->package_id = $package->id;
        $booking->offer_id = $offer->id;
        $booking->product_id = $longtripAccommodationPricing->id;
        $booking->product_type = get_class($longtripAccommodationPricing);
        $booking->product_dates = null;
        $booking->promocode_id = $promocode->id ?? null;
        $booking->currency_id = $currency->id;
        $booking->client_id = $clientId;
        $booking->passengers = $passengers;
        $booking->starts_at = $this->getStartDate($longtripBoardingLocation, null);
        $booking->subtotal = app(OfferRepository::class)->getPaymentTotal($package, $offer, collect($additionalIds)->flatten()->toArray(), [$longtripAccommodationPricing, $longtripBoardingLocation], null, $passengers);
        $booking->discount = app(PromocodeRepository::class)->getDiscount($promocode, $booking->currency);
        $booking->total = $this->getBookingTotal($booking);
        $booking->quotations = json_encode(app(CurrencyQuotationRepository::class)->list()->toArray());
        $booking->ip = $ip;

        $additionalIds = $this->parseAdditionalIds($additionalIds);

        $booking->bookingClient     = $this->makeBookingClient($booking);
        $booking->bookingPassengers = $this->makeBookingPassengers($booking, $additionalIds);
        $booking->bookingOffer      = $this->makeBookingOffer($booking, $package, $offer, [$longtripAccommodationPricing, $longtripBoardingLocation], null);
        $booking->bookingProducts   = $this->makeBookingProducts($booking, $offer, $currency, [$longtripAccommodationPricing, $longtripBoardingLocation], null);
        $booking->product           = $longtripAccommodationPricing;

        $this->checkPassengersAdditionalStock($booking);

        return $booking;
    }

    /**
     * [makeBookingOffer description]
     *
     * @param   Booking       $booking        [$booking description]
     * @param   Package       $package        [$package description]
     * @param   Offer         $offer          [$offer description]
     * @param   [type]        $product        [$product description]
     * @param   array         $selectedDates  [$selectedDates description]
     *
     * @return  BookingOffer                  [return description]
     */
    public function makeBookingOffer(Booking $booking, Package $package, Offer $offer, $product, ?array $selectedDates): BookingOffer
    {
        $bookingOffer = new BookingOffer;
        $bookingOffer->booking_id       = $booking->id;
        $bookingOffer->offer_id         = $booking->offer_id;
        $bookingOffer->company_id       = $offer->company_id;
        $bookingOffer->currency_id      = $booking->currency_id;
        $currency                       = $offer->getCurrency();
        $bookingOffer->currency_origin_id  = ($currency)?$currency->id:null;
        $bookingOffer->price_net        = app(OfferRepository::class)->getOfferTotal($package, $offer, $product, $selectedDates, 1);
        $bookingOffer->price            = app(OfferRepository::class)->getOfferTotal($package, $offer, $product, $selectedDates);

        return $bookingOffer;
    }

    /**
     * [makeBookingProducts description]
     *
     * @param   Booking     $booking        [$booking description]
     * @param   [type]      $product        [$product description]
     * @param   array       $selectedDates  [$selectedDates description]
     *
     * @return  SupportCollection                  [return description]
     */
    public function makeBookingProducts(Booking $booking, Offer $offer, Currency $currency, $product, ?array $selectedDates): SupportCollection
    {
        $bookingProducts = collect();

        if (is_array($product)) {
            foreach ($product as $p) {
                $bookingProduct = new BookingProduct;
                $bookingProduct->booking_id         = $booking->id;
                $bookingProduct->company_id         = $offer->company_id;
                $bookingProduct->currency_id        = $booking->currency_id;
                $currencyOffer                      = $offer->getCurrency();
                $bookingProduct->currency_origin_id    = ($currencyOffer)?$currencyOffer->id:null;;
                $bookingProduct->product_type       = get_class($p);
                $bookingProduct->product_id         = $p->id;
                $bookingProduct->sale_coefficient   = $p->getSaleCoefficient();
                $bookingProduct->price              = moneyFloat($p->getPrice(), $currency, $offer->currency); 
                $bookingProduct->price_net          = moneyFloat($p->getPriceNet(), $currency, $offer->currency);

                $bookingProduct->date               = $booking->starts_at;
                $bookingProducts->push($bookingProduct);

                if (!app(BookingProductRepository::class)->hasStock($bookingProduct)) {
                    throw new NoStockException;
                }
            }

            return $bookingProducts;
        }

        if (empty($selectedDates)) {
            $bookingProduct = new BookingProduct;
            $bookingProduct->booking_id         = $booking->id;
            $bookingProduct->company_id         = $offer->company_id;
            $bookingProduct->currency_id        = $booking->currency_id;
            $currencyOffer                      = $offer->getCurrency();
            $bookingProduct->currency_origin_id = ($currencyOffer)?$currencyOffer->id:null;;
            $bookingProduct->product_type       = $booking->product_type;
            $bookingProduct->product_id         = $booking->product_id;
            $bookingProduct->sale_coefficient   = $product->getSaleCoefficient();
            $bookingProduct->price              = moneyFloat($product->getPrice(), $currency, $offer->currency);
            $bookingProduct->price_net          = moneyFloat($product->getPriceNet(), $currency, $offer->currency);
            $bookingProduct->date               = $booking->starts_at;
            $bookingProducts->push($bookingProduct);

            if (!app(BookingProductRepository::class)->hasStock($bookingProduct)) {
                throw new NoStockException;
            }

            return $bookingProducts;
        }

        foreach ($selectedDates as $date) {
            $bookingProduct = new BookingProduct;
            $bookingProduct->booking_id         = $booking->id;
            $bookingProduct->currency_id        = $booking->currency_id;
            $currency                           = $offer->getCurrency();
            $bookingProduct->currency_origin_id    = ($currency)?$currency->id:null;;
            $bookingProduct->company_id         = $offer->company_id;
            $bookingProduct->product_type       = $booking->product_type;
            $bookingProduct->product_id         = $booking->product_id;
            $bookingProduct->sale_coefficient   = $product->getSaleCoefficient();
            $bookingProduct->price              = moneyFloat($product->getPrice($date), $booking->currency);
            $bookingProduct->price_net          = moneyFloat($product->getPriceNet($date), $booking->currency);
            $bookingProduct->date               = $date;
            $bookingProducts->push($bookingProduct);

            if (!app(BookingProductRepository::class)->hasStock($bookingProduct)) {
                throw new NoStockException;
            }
        }

        return $bookingProducts;
    }

    /**
     * [makeBookingClient description]
     *
     * @param   Booking        $booking  [$booking description]
     *
     * @return  BookingClient            [return description]
     */
    public function makeBookingClient(Booking $booking): BookingClient
    {
        $client = Client::find($booking->client_id);

        $bookingClient = new BookingClient;
        $bookingClient->booking_id = $booking->id;
        $bookingClient->client_id = $booking->client_id;
        $bookingClient->name = $client->name;
        $bookingClient->company_name = $client->company_name;
        $bookingClient->legal_name = $client->legal_name;
        $bookingClient->email = $client->email;
        $bookingClient->phone = $client->contacts()->first()->value ?? null;
        $bookingClient->birthdate = $client->birthdate;
        $bookingClient->identity = $client->identity;
        $bookingClient->uf = $client->uf;
        $bookingClient->primary_document = $client->primary_document;
        $bookingClient->document = $client->document;
        $bookingClient->passport = $client->passport;
        $bookingClient->registry = $client->registry;
        $bookingClient->address = $client->address->address;
        $bookingClient->address_number = $client->address->number;
        $bookingClient->address_neighborhood = $client->address->neighborhood;
        $bookingClient->address_complement = $client->address->complement;
        $bookingClient->address_city = $client->address->city;
        $bookingClient->address_state = $client->address->state;
        $bookingClient->address_zip = $client->address->zip;
        $bookingClient->address_country = $client->address->country;

        return $bookingClient;
    }

    /**
     * [makeBookingPassengers description]
     *
     * @param   Booking     $booking  [$booking description]
     *
     * @return  SupportCollection            [return description]
     */
    public function makeBookingPassengers(Booking $booking, ?array &$additionalIds): SupportCollection
    {
        $bookingPassengers      = collect();

        $bookingPassenger       = new BookingPassenger;

        $bookingPassenger->booking_id = $booking->id;
        $bookingPassenger->name = $booking->bookingClient->name;
        $bookingPassenger->email = $booking->bookingClient->email;
        $bookingPassenger->phone = $booking->bookingClient->phone;
        $bookingPassenger->birthdate = $booking->bookingClient->birthdate;
        $bookingPassenger->identity = $booking->bookingClient->identity;
        $bookingPassenger->uf = $booking->bookingClient->uf;
        $bookingPassenger->document = $booking->bookingClient->document;
        $bookingPassenger->primary_document = $booking->bookingClient->primary_document;
        $bookingPassenger->passport = $booking->bookingClient->passport;
        $bookingPassenger->address = $booking->bookingClient->address;
        $bookingPassenger->address_number = $booking->bookingClient->address_number;
        $bookingPassenger->address_neighborhood = $booking->bookingClient->address_neighborhood;
        $bookingPassenger->address_complement = $booking->bookingClient->address_complement;
        $bookingPassenger->address_city = $booking->bookingClient->address_city;
        $bookingPassenger->address_state = $booking->bookingClient->address_state;
        $bookingPassenger->address_zip = $booking->bookingClient->address_zip;
        $bookingPassenger->address_country = $booking->bookingClient->address_country;


        if (!empty($additionalIds)) {
            $additionalIds          = array_values($additionalIds);
            $bookingPassenger->bookingPassengerAdditionals = $this->makeBookingPassengerAdditionals($booking, $bookingPassenger, $additionalIds[0]);
        }

        $bookingPassengers->push($bookingPassenger);

        for ($i = 1; $i < $booking->passengers; $i++) {
            $bookingPassenger = new BookingPassenger;
            $bookingPassenger->booking_id = $booking->id;

            if (!empty($additionalIds)) {
                $bookingPassenger->bookingPassengerAdditionals = $this->makeBookingPassengerAdditionals($booking, $bookingPassenger, $additionalIds[$i]);
            }

            $bookingPassengers->push($bookingPassenger);
        }

        return $bookingPassengers;
    }

    /**
     * [getStartDate description]
     *
     * @param   object  $product        [$product description]
     * @param   array   $selectedDates  [$selectedDates description]
     *
     * @return  [type]                  [return description]
     */
    public function getStartDate(object $product, ?array $selectedDates)
    {
        if (empty($selectedDates)) {
            return $product->boarding_at;
        }

        $dates = [];

        foreach ($selectedDates as $date) {
            $dates[] = Carbon::createFromFormat('Y-m-d', $date);
        }

        $earlyDate = $dates[0];

        foreach ($dates as $date) {
            if ($date < $earlyDate) {
                $earlyDate = $date;
            }
        }

        return $earlyDate;
    }

    /**
     * [makeBookingPassengerAdditionals description]
     *
     * @return  [type]  [return description]
     */
    public function makeBookingPassengerAdditionals(Booking $booking, BookingPassenger $bookingPassenger, ?array &$additionalIds)
    {
        $bookingPassengerAdditionals = collect();

        foreach ($additionalIds as $key => $additionalId) {
            $additional = app(AdditionalRepository::class)->find($additionalId);
            
            if (empty($additional)) {
                unset($additionalIds[$key]);
                continue;
            }

            if (!$additional->hasStock()) {
                $this->setWarningNoStockException(1);
                unset($additionalIds[$key]);
                continue;
            }
            $stock      = 1;
            if(!$this->checkAdditionalStock($additional->id, $additional->getStock(), $stock)){
                // Rule: if there are no stock available in the additional, the remain passenger will not have this additional added in their package.
                $this->setWarningNoStockException(1);
                unset($additionalIds[$key]);
                continue;
            }
            $this->putAdditionalStock($additional->id, 1);

            $currency                                           = $additional->getCurrency();
            $bookingPassengerAdditional = new BookingPassengerAdditional;
            $bookingPassengerAdditional->booking_id             = $bookingPassenger->booking_id;
            $bookingPassengerAdditional->booking_passenger_id   = $bookingPassenger->id;
            $bookingPassengerAdditional->additional_id          = $additional->id;
            $bookingPassengerAdditional->currency_id            = $booking->currency_id;
            $bookingPassengerAdditional->currency_origin_id        = ($currency)?$currency->id:null;
            $bookingPassengerAdditional->company_id             = $additional->offer->company_id;
            $bookingPassengerAdditional->additional             = $additional;
            $bookingPassengerAdditional->sale_coefficient       = $additional->getSaleCoefficient();
            $bookingPassengerAdditional->price                  = moneyFloat($additional->getPrice(), $booking->currency, $additional->currency);
            $bookingPassengerAdditional->price_net              = moneyFloat($additional->getPriceNet(), $booking->currency, $additional->currency);

            $bookingPassengerAdditionals->push($bookingPassengerAdditional);
        }

        return $bookingPassengerAdditionals;
    }

    /**
     * [parseAdditionalIds description]
     *
     * @param   array  $additionalIds  [$additionalIds description]
     *
     * @return  [type]                 [return description]
     */
    protected function parseAdditionalIds(?array $additionalIds): ?array
    {
        if (empty($additionalIds)) {
            return null;
        }

        $additionals = collect($additionalIds);

        foreach ($additionals as $key => $additional) {
            $additionals[$key] = collect($additional)->flatten(0);
        }

        return $additionals->toArray();
    }

    /**
     * [getBookingTotal description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  float              [return description]
     */
    public function getBookingTotal(Booking $booking): float
    {
        $subtotal = $booking->subtotal ?? 0;
        $discount = $booking->discount ?? 0;

        if ($discount >= $subtotal) {
            return 0;
        }

        return $subtotal - $discount;
    }

    /**
     * [fillBooking description]
     *
     * @param   Booking  $booking     [$booking description]
     * @param   array    $attributes  [$attributes description]
     *
     * @return  Booking               [return description]
     */
    public function fillBooking(Booking $booking, array $attributes): Booking
    {
        $booking->comments          = $attributes['obs'] ?? null;
        $booking->check_contract    = $attributes['check_contract'] ?? null;

        foreach ($booking->bookingPassengers as $key => $bookingPassenger) {
            foreach ($bookingPassenger->bookingPassengerAdditionals as $bookingPassengerAdditional) {
                if (!$bookingPassengerAdditional->additional->hasStock()) {
                    throw new NoStockException;
                }
            }

            $booking->bookingPassengers[$key]->name = $attributes['passenger'][$key + 1]['nome'];
            $booking->bookingPassengers[$key]->identity = $attributes['passenger'][$key + 1]['rg'] ?? null;
            $booking->bookingPassengers[$key]->uf = $attributes['passenger'][$key + 1]['est_emissor'] ?? null;
            $booking->bookingPassengers[$key]->document = $attributes['passenger'][$key + 1]['cpf'] ?? null;
            $booking->bookingPassengers[$key]->passport = $attributes['passenger'][$key + 1]['passaporte'] ?? null;
            $booking->bookingPassengers[$key]->birthdate = Carbon::createFromFormat('d/m/Y', $attributes['passenger'][$key + 1]['data_nascimento']);
            $booking->bookingPassengers[$key]->phone = $attributes['passenger'][$key + 1]['fone'];
            $booking->bookingPassengers[$key]->email = $attributes['passenger'][$key + 1]['email'];
        }

        return $booking;
    }

    /**
     * [storeBooking description]
     *
     * @param   Booking  $booking            [$booking description]
     * @param   array    $attributes         [$attributes description]
     * @param   array    $paymentAttributes  [$paymentAttributes description]
     *
     * @return  Booking                      [return description]
     */
    public function storeBooking(Booking $booking, array $attributes, array $paymentAttributes = []): Booking
    {
        try {
            DB::beginTransaction();

            $paymentMethod              = app(PaymentMethodRepository::class)->find($paymentAttributes['payment_method_id']);
            $paymentMethod = $booking->package->paymentMethods()
                ->where("payment_method_id", $paymentAttributes['payment_method_id'])
                ->withPivot(["payment_method_id", "processor", "tax", "discount", 
                "limiter", "max_installments", "first_installment_billet",
                "first_installment_billet_processor", "first_installment_billet_method_id",
                "is_active"])
                ->first()
                ;
            $paymentMethodInstallments  = $paymentMethod->getBookingInstallments($booking);
            
            $installments               = $paymentMethodInstallments[$paymentAttributes['installments']];

            $booking->installments      = count($installments);
            $booking->expired_at        = Carbon::now()->addDays($booking->package->payment_expire_days);

            // Process payment information
            $booking->subtotal          = $installments[1]['subtotal'];
            $booking->tax               = $installments[1]['totalTax'];
            $booking->discount          = $installments[1]['discount_value'];
            $booking->discount_promocode= 0;
            $booking->total             = $installments[1]['total'] - $installments[1]['discount_value'];
            // END Process payment information
            
            $bookingPassengers  = $booking->bookingPassengers;
            $bookingClient      = $booking->bookingClient;
            $bookingOffer       = $booking->bookingOffer;
            $bookingProducts    = $booking->bookingProducts;

            $booking->status            = ProcessStatus::CONFIRMED;
            // Rule 29/12: When a customer sign the check_contract flag, document_status fields turns to "Confirmed".
            $booking->document_status    = ProcessStatus::CONFIRMED;

            // Promocode
            if($booking->promocode != null){
                $booking->discount_promocode = $booking->promocode->discount_value;
            }
            // End Promocode
            $booking            = $this->store($booking->toArray());

            // Flag for post booking generation payments (Eg: paypal)
            $this->postponePayment  = BookingPayments::POSTPONE_PAYMENT;

            // Storing booking client
            $bookingClient      = $this->storeBookingClient($booking, $bookingClient);
            $bookingOffer       = $this->storeBookingOffer($booking, $bookingOffer);
            $bookingPassengers  = $this->storeBookingPassengers($booking, $bookingPassengers);
            $bookingBills       = $this->storeBookingBills($booking, $installments, $paymentMethod);
            $bookingProducts    = $this->storeBookingProducts($booking, $bookingProducts);

            $this->processBookingPayment($booking, $paymentMethod, $paymentAttributes, $installments);
            $this->processEmailInformaton($booking);

            $this->logging->bookingCreated($booking);
            $this->logging->bookingDigitalSigned($booking);

            DB::commit();
            /**
             * This routine was created because some payments needs the booking
             * created and will redirect to site for payment.
             * Rods 30/10/21
             */
            if($this->postponePayment == BookingPayments::POSTPONE_WAITING){
                $this->processBookingPayment($booking, $paymentMethod, $paymentAttributes, $installments);
            }
            return $booking->refresh();
        } catch (Throwable $ex) {
            bugtracker()->notifyException($ex);
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * [getActiveBookings description]
     *
     * @param   [type]$client  [$client description]
     * @param   null           [ description]
     *
     * @return  [type]         [return description]
     */
    public function getActiveBookings($client = null)
    {
        $today = Carbon::today();

        $query = $this->model->where(function ($query) use ($today) {
            $query->whereRaw("DATE(starts_at) >= DATE('{$today}')");
            $query->orWhere('starts_at', null);
        });

        

        if (!empty($client)) {
            $clientId = $client instanceof Client ? $client->id : $client;

            $query->where('client_id', $clientId);
        }

        $query->orderBy("id", "DESC");
        $bookings = $query->get();

        return $bookings;
    }

    /**
     * [getPastBookings description]
     *
     * @param   Client  $client  [$client description]
     * @param   null             [ description]
     *
     * @return  [type]           [return description]
     */
    public function getPastBookings(?Client $client = null)
    {
        $today = Carbon::today();

        $query = $this->model->where(function ($query) use ($today) {
            $query->whereRaw("DATE(starts_at) <= DATE('{$today}')");
            $query->orWhere('starts_at', null);
        });

        $query->where(function ($query) {
            $query->whereIn('status', [ProcessStatus::CANCELED, ProcessStatus::SUSPENDED]);
        });

        if (!empty($client)) {
            $clientId = $client instanceof Client ? $client->id : $client;

            $query->where('client_id', $clientId);
        }

        $bookings = $query->get();

        return $bookings;
    }

    /**
     * [getPastBookings description]
     *
     * @param   Client  $client  [$client description]
     * @param   null             [ description]
     *
     * @return  [type]           [return description]
     */
    public function getBookingLegacies(?Client $client = null)
    {
        $today = Carbon::today();
        $query = $this->modelBookingLegacies->orderBy("id", "DESC");
        
        if (!empty($client)) {
            $clientId = $client instanceof Client ? $client->id : $client;

            $query->where('client_id', $clientId);
        }

        $bookings = $query->get();

        return $bookings;
    }

    /**
     * [cancel description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function cancel(Booking $booking)
    {
        if (!$booking->canBeCanceled()) {
            return;
        }

        try {
            DB::beginTransaction();

            $booking->status = ProcessStatus::CANCELED;
            $booking->save();

            foreach ($booking->bookingBills as $bookingBill) {
                $bookingBill->status = ProcessStatus::CANCELED;
                $bookingBill->save();
            }

            foreach ($booking->bookingProducts as $bookingProduct) {
                app(BookingProductRepository::class)->putStock($bookingProduct, $booking->passengers);
            }

            foreach ($booking->bookingPassengerAdditionals as $bookingPassengerAdditional) {
                app(BookingPassengerAdditional::class)->putStock($bookingPassengerAdditional);
            }

            $this->logging->bookingCanceled($booking);

            DB::commit();
        } catch (Throwable $ex) {
            bugtracker()->notifyException($ex);
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {

        if(isset($attributes["product_id"]) && $attributes["product_id"] >0){
            $resource->offer_id = $resource->getProduct()->offer_id;
        }
        return $resource;
    }

    public function onBeforeDelete(Model $booking): Model
    {
        if (!$booking->canBeDeleted()) {
            // 160921 - Removed here for John proceed to remove bookings.
            //return $booking;
        }

        try {
            DB::beginTransaction();

            $booking->bookingBillRefunds()->delete();
            $booking->bookingBills()->delete();
            $booking->bookingClient()->delete();
            $booking->bookingOffer()->delete();
            $booking->bookingPassengerAdditionals()->delete();
            $booking->bookingPassengers()->delete();
            $booking->bookingProducts()->delete();
            $booking->bookingVouchers()->delete();
            
            DB::commit();

            return $booking;
        } catch (\Throwable $ex) {
            bugtracker()->notifyException($ex);
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        if(isset($attributes["product_type"]) && ($resource->product_type != $attributes["product_type"])){
            $attributes["product_id"]       = 0;
            $attributes["product_dates"]    = [];
            $this->destroyBookingProducts($resource, $resource->bookingProducts->all());
        }
        if(isset($attributes['discount'])){
            $attributes['discount'] = sanitizeMoney($attributes['discount']);
        }
        if(isset($attributes['tax'])){
            $attributes['tax']      = sanitizeMoney($attributes['tax']);
        }
        if(isset($attributes['discount_promocode'])){
            $attributes['discount_promocode']   = sanitizeMoney($attributes['discount_promocode']);
        }
        if(isset($attributes['discount_promocode_provider'])){
            $attributes['discount_promocode_provider']   = sanitizeMoney($attributes['discount_promocode_provider']);
        }

        if($resource->product_id == 0 && $resource->bookingProducts->count() >0){
            $product = $resource->bookingProducts->first();
            $resource->product_id = $product->product_id;
        }

        return $attributes;
    }

    /**
     * [refreshBooking description]
     *
     * @param   Booking  $booking
     * @param   array    $bookingVoucherFiles
     *
     * @return  void
     */
    public function refreshBooking(Booking $booking)
    {
        if($booking->product_id >0){
            $booking->offer_id = $booking->getProduct()->getOffer()->id;
        }
        $booking->save();
    }

    /**
     * [uploadVoucherFiles description]
     *
     * @param   Booking  $booking
     * @param   array    $bookingVoucherFiles
     *
     * @return  void
     */
    public function uploadVoucherFiles(Booking $booking, array $bookingVoucherFiles)
    {
        $repository = app(BookingVoucherFileRepository::class);

        foreach ($bookingVoucherFiles as $file) {
            $voucherFile = $repository->setBooking($booking)->store([
                'voucher' => $file,
            ]);
        }
    }



    /**
     * [checkPassengersAdditionalStock description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function checkPassengersAdditionalStock(Booking $booking)
    {
        $stocks = [];

        foreach ($booking->bookingPassengers as $bookingPassenger) {
            foreach ($bookingPassenger->bookingPassengerAdditionals as $bookingPassengerAdditional) {
                $additionalStock = $bookingPassengerAdditional->additional->stock;

                $stocks[$additionalStock] = isset($stocks[$additionalStock]) ? $stocks[$additionalStock] + 1 : 1;
            }
        }

        foreach ($stocks as $stock => $bookingQuantity) {
            if ($bookingQuantity > $stock) {
                $this->setWarningNoStockException(1);
            }
        }
    }

    /**
     * [changePaymentMethod description]
     *
     * @param   Booking  $booking            [$booking description]
     * @param   array    $paymentAttributes  [$paymentAttributes description]
     *
     * @return  [type]                       [return description]
     */
    public function changePaymentMethod(Booking $booking, array $paymentAttributes)
    {
        if (!$booking->canChangePaymentMethod()) {
            return;
        }

        try {
            DB::beginTransaction();

            $paymentMethod = app(PaymentMethodRepository::class)->find($paymentAttributes['payment_method_id']);
            $paymentMethod = $booking->package->paymentMethods()
                ->where("payment_method_id", $paymentAttributes['payment_method_id'])
                ->withPivot(["payment_method_id", "processor", "tax", "discount", 
                "limiter", "max_installments", "first_installment_billet",
                "first_installment_billet_processor", "first_installment_billet_method_id",
                "is_active"])
                ->first()
                ;
            $paymentMethodInstallments = $paymentMethod->getBookingInstallments($booking);
            
            $installments = $paymentMethodInstallments[$paymentAttributes['installments']];
            $booking->installments = count($installments);

            $booking->bookingBills()->delete();
            
            $this->storeBookingBills($booking, $installments, $paymentMethod);
            if(sizeof($installments) >1){
                $processor = $installments[sizeof($installments) - 1]['processor'];
            }else{
                $processor = $installments[1]['processor'];
            }

            if ($paymentMethod->isCredit()) {
                ClientPaymentDataset::create([
                    'booking_id' => $booking->id,
                    'client_id' => $booking->bookingClient->client_id,
                    'processor' => $processor,
                    'payload' => json_encode($paymentAttributes),
                ]);
            }
            DB::commit();
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * [setAsPaid description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]                     [return description]
     */
    public function setAsPaid(Booking $booking): Booking
    {
        $booking->payment_status = ProcessStatus::CONFIRMED;
        $booking->save();

        $this->logging->bookingPaid($booking);

        return $booking;
    }

    
    /**
     * Get store information used in the booking reservation email
     *
     * @return  array
     */ 
    public function getEmailData()
    {
        return $this->_emailData;
    }

    /**
     * Set store information used in the booking reservation email
     *
     * @param  array  $_emailData  Store information used in the booking reservation email
     *
     * @return  self
     */ 
    public function addEmailData($_emailData)
    {
        if(is_array($_emailData)){
            $this->_emailData = array_merge($this->_emailData, $_emailData);
        }else{
            $this->_emailData[] = $_emailData;
        }

        return $this;
    }

    /**
     * Send email information for provider or customer
     *
     * @param  Booking  $booking  $booking
     * @param  array  $_data  Store information used in the booking reservation email
     *
     * @return  self
     */ 
    public function sendBookingNotification(Booking $booking, $_data)
    {
        $client     = $booking->bookingClient;
        switch($_data['type']){
            case BookingNotifications::NOTIFICATION_CLIENT:
                Mail::to($client->email)->send(new BookingNotificationMail($booking, null, $_data['type']));
                break;
            case BookingNotifications::NOTIFICATION_PROVIDER:
            default:
                // Offer provider
                $provider = $booking->offer->provider;
                Mail::to($provider->email)->send(new BookingNotificationMail($booking, $provider, $_data['type']));
                $_providers[] = $provider->id;
                if($booking->bookingProducts){
                    foreach($booking->bookingProducts as $bookingProduct){
                        $provider = $bookingProduct->getProduct()->getOffer()->provider;
                        if(!in_array($provider->id, $_providers)){
                            Mail::to($provider->email)->send(new BookingNotificationMail($booking, $provider, $_data['type']));
                        }
                        $_providers[] = $provider->id;

                    }
                }
                
                break;
        }

        return $this;
    }

    /**
     * [recalculate description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]                     [return description]
     */
    public function recalculate(Booking $booking)
    {
        $sumProducts = $sumNetProducts = 0;
        $passengers  = $booking->bookingPassengers->count();
        // Totals of the Booking
        foreach ($booking->bookingProducts as $key => $bookingProduct) {
            $sumProducts    += $bookingProduct->price  * $passengers;
            $sumNetProducts += $bookingProduct->price_net  * $passengers;
        }
        foreach ($booking->bookingPassengerAdditionals as $key => $bookingAdditional) {
            $sumProducts    += $bookingAdditional->price  ;
            $sumNetProducts += $bookingAdditional->price_net;
        }
        $taxValue           = $booking->tax;
        $discountValue      = $booking->discount;
        $discountValue      += $booking->discount_promocode;
        $discountValue      += $booking->discount_promocode_provider;
        $baseTotal          = ($sumProducts + $taxValue) - ($discountValue);
        $booking->subtotal  = $sumProducts;
        $booking->total     = $baseTotal;
        $booking->save();
        $bookingOffer   = $booking->bookingOffer;
        if(!$bookingOffer){
            $bookingOffer = new BookingOffer();
            $bookingOffer->fill([
                'booking_id'    => $booking->id,
                'offer_id'      => $booking->offer_id,
                'currency_id'   => $booking->currency_id,
                'currency_origin_id'    => $booking->currency_id,
                'company_id'    => $booking->offer->company_id
            ]);
            $bookingOffer->save();
        }
        $bookingOffer->price        = $baseTotal;
        $bookingOffer->price_net    = $sumNetProducts;
        $bookingOffer->save();
        // Totals of the Booking Bills
        /*
         * Removed this in 10/02/2022 because this function will recalculate only booking. 
        $baseTotal          = $sumProducts - $discountValue;
        $installments       = $booking->bookingBills()->count();
        if($installments > 0){
            $installmentValue   = $baseTotal/$installments;
            $installmentTax     = $taxValue/$installments;
            foreach($booking->bookingBills as $bookingBill){
                $bookingBill->total = $installmentValue + $installmentTax;
                $bookingBill->save();
            }
        }
        */
        return $this;
    }

}
