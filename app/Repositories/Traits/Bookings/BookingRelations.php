<?php

namespace App\Repositories\Traits\Bookings;

use App\Enums\Bookings\BookingPayments;
use App\Enums\Processor;
use App\Events\Bookings\BookingCreatedEvent;
use App\Events\Bookings\BookingStatusEvent;
use App\Exceptions\NoStockException;
use App\Exceptions\PaymentErrorException;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Models\BookingClient;
use App\Models\ClientPaymentDataset;
use App\Models\LongtripAccommodationsPricing;
use App\Repositories\BookingBillRepository;
use App\Repositories\BookingClientRepository;
use App\Repositories\BookingOfferRepository;
use App\Repositories\BookingPassengerAdditionalRepository;
use App\Repositories\BookingPassengerRepository;
use App\Repositories\BookingProductRepository;
use App\Repositories\BookingVoucherRepository;
use App\Services\PaymentGateway\Providers\Paypal;
use Carbon\Carbon;
use Exception;

trait BookingRelations
{
    /**
     * [storeBookingClient description]
     *
     * @param   [type]  $booking        [$booking description]
     * @param   [type]  $bookingClient  [$bookingClient description]
     *
     * @return  [type]                  [return description]
     */
    public function storeBookingClient($booking, $bookingClient)
    {
        $bookingClient->booking_id = $booking->id;
        $bookingClient = app(BookingClientRepository::class)->store($bookingClient->toArray());

        return $bookingClient;
    }

    /**
     * [updateBookingClient description]
     *
     * @param   BookingClient  $bookingClient            [$bookingClient description]
     * @param   [type]         $bookingClientAttributes  [$bookingClientAttributes description]
     *
     * @return  [type]                                   [return description]
     */
    public function updateBookingClient(BookingClient $bookingClient, $bookingClientAttributes)
    {
        unset($bookingClientAttributes['booking_id']);

        return app(BookingClientRepository::class)->update($bookingClient, $bookingClientAttributes);
    }

    /**
     * [storeBookingClient description]
     *
     * @param   [type]  $booking        [$booking description]
     * @param   [type]  $bookingClient  [$bookingClient description]
     *
     * @return  [type]                  [return description]
     */
    public function storeBookingOffer($booking, $bookingOffer)
    {
        $bookingOffer->booking_id = $booking->id;
        $bookingOffer = app(BookingOfferRepository::class)->store($bookingOffer->toArray());

        return $bookingOffer;
    }

    /**
     * [storeBookingClient description]
     *
     * @param   [type]  $booking        [$booking description]
     * @param   [type]  $bookingClient  [$bookingClient description]
     *
     * @return  [type]                  [return description]
     */
    public function storeBookingPassengers($booking, $bookingPassengers)
    {
        $passengers = collect();

        foreach ($bookingPassengers as $bookingPassenger) {
            $bookingPassengerAdditionals = $bookingPassenger->bookingPassengerAdditionals;
            $bookingPassenger->booking_id = $booking->id;
            $bookingPassenger = app(BookingPassengerRepository::class)->store($bookingPassenger->toArray());

            foreach ($bookingPassengerAdditionals ?? [] as $bookingPassengerAdditional) {
                $bookingPassengerAdditional->booking_id = $booking->id;
                $bookingPassengerAdditional->booking_passenger_id = $bookingPassenger->id;
                $bookingPassengerAdditional = app(BookingPassengerAdditionalRepository::class)->store($bookingPassengerAdditional->toArray());
            }

            $passengers->push($bookingPassenger);
        }

        return $passengers;
    }

    /**
     * [updateBookingPassengers description]
     *
     * @param   Booking  $booking                     [$booking description]
     * @param   [type]   $bookingPassengerAttributes  [$bookingPassengerAttributes description]
     *
     * @return  [type]                                [return description]
     */
    public function updateBookingPassengers(Booking $booking, $bookingPassengerAttributes)
    {
        $passengers = collect();

        foreach ($bookingPassengerAttributes as $id => $attributes) {
            unset($attributes['booking_id']);
            $bookingPassenger = app(BookingPassengerRepository::class)->find($id);
            $bookingPassenger = app(BookingPassengerRepository::class)->update($bookingPassenger, $attributes);
            $passengers->push($bookingPassenger);
        }

        return $passengers;
    }

    /**
     * [updateBookingPassengerAdditionals description]
     *
     * @param   Booking  $booking                                [$booking description]
     * @param   [type]   $bookingPassengerAdditionalsAttributes  [$bookingPassengerAdditionalsAttributes description]
     *
     * @return  [type]                                            [return description]
     */
    public function updateBookingPassengerAdditionals($booking, $bookingPassengerAdditionalsAttributes)
    {
        $passengerAdditionals = collect();

        foreach ($bookingPassengerAdditionalsAttributes as $id => $attributes) {
            $attributes['booking_id'] = $booking->id;
            $attributes['currency_id'] = $booking->currency_id;
            $bookingPassengerAdditional = app(BookingPassengerAdditionalRepository::class)->find($id);
            $bookingPassengerAdditional = app(BookingPassengerAdditionalRepository::class)->update($bookingPassengerAdditional, $attributes);
            $passengerAdditionals->push($bookingPassengerAdditional);
        }

        return $passengerAdditionals;
    }

    /**
     * [updateBookingVouchers description]
     *
     * @param   [type]  $booking                    [$booking description]
     * @param   [type]  $bookingVouchersAttributes  [$bookingVouchersAttributes description]
     *
     * @return  [type]                              [return description]
     */
    public function updateBookingVouchers($booking, $bookingVouchersAttributes)
    {
        $vouchers = collect();

        foreach ($bookingVouchersAttributes as $id => $attributes) {
            $attributes['booking_id'] = $booking->id;
            $bookingVoucher = app(BookingVoucherRepository::class)->find($id);
            $bookingVoucher = app(BookingVoucherRepository::class)->update($bookingVoucher, $attributes);
            $vouchers->push($bookingVoucher);
        }

        return $vouchers;
    }

    /**
     * [storeBookingClient description]
     *
     * @param   [type]  $booking        [$booking description]
     * @param   [type]  $bookingClient  [$bookingClient description]
     *
     * @return  [type]                  [return description]
     */
    public function storeBookingBills($booking, $installments, $paymentMethod)
    {
        $bills = collect();
        $now = Carbon::createFromFormat('Y-m-d', $booking->expired_at->format('Y-m-d'))->addMonth(-1);
        $processor = "";
        $installment_payment = 1;
        if($paymentMethod->pivot){
            $paymentMethod = $paymentMethod->pivot;
        }
        foreach ($installments ?? [] as $key => $installment) {
            if($installment_payment == 1 && $paymentMethod->first_installment_billet){
                $processor = $paymentMethod->first_installment_billet_processor;
                $installment_payment = 1;
                $paymentMethodId = $paymentMethod->first_installment_billet_method_id;
            }
            if($installment['processor'] != $processor){
                $processor = $installment['processor'];
                $installment_payment = 1;
                $paymentMethodId = $installment['payment_method_id'];
            }
            $bookingBill = new BookingBill();
            $bookingBill->booking_id        = $booking->id;
            $bookingBill->client_id         = $booking->client_id;
            $bookingBill->payment_method_id = $paymentMethodId;
            $bookingBill->currency_id       = $booking->currency_id;
            $bookingBill->total             = (float) $installment['value'];
            $bookingBill->status            = 'pending';
            $bookingBill->installment       = $installment_payment;
            $bookingBill->ct                = $key;
            $bookingBill->expires_at        = $now->addMonth();
            $bookingBill->processor         = $installment['processor'];
            $bookingBill = app(BookingBillRepository::class)->skipMoneyFormat()->store($bookingBill->toArray());

            $bills->push($bookingBill);
            $installment_payment++;
        }
        return $bills;
    }

    /**
     * [updateBookingBills description]
     *
     * @param   Booking  $booking                 [$booking description]
     * @param   [type]   $bookingBillsAttributes  [$bookingBillsAttributes description]
     *
     * @return  [type]                            [return description]
     */
    public function updateBookingBills(Booking $booking, $bookingBillsAttributes)
    {
        $bills = collect();

        foreach ($bookingBillsAttributes as $id => $attributes) {
            $attributes['tax']                  = 0;
            $attributes['total']                = sanitizeMoney($attributes['total']);

            unset($attributes['booking_id']);

            $bookingBill = app(BookingBillRepository::class)->find($id);
            $bookingBill = app(BookingBillRepository::class)->update($bookingBill, $attributes);
            $bills->push($bookingBill);
        }

        return $bills;
    }

    /**
     * [storeBookingProducts description]
     *
     * @param   [type]  $booking          [$booking description]
     * @param   [type]  $bookingProducts  [$bookingProducts description]
     *
     * @return  [type]                    [return description]
     */
    public function storeBookingProducts($booking, $bookingProducts)
    {
        $products = collect();

        foreach ($bookingProducts as $bookingProduct) {
            $bookingProduct->booking_id = $booking->id;
            if (!app(BookingProductRepository::class)->hasStock($bookingProduct, $booking->passengers)) {
                throw new NoStockException(__('backend.booking.no_stock_product', ["product" => $bookingProduct->getTitle()]));
            }
            $bookingProduct = app(BookingProductRepository::class)->store($bookingProduct->toArray());
            $products->push($bookingProduct);

            app(BookingProductRepository::class)->pickStock($bookingProduct, $booking->passengers);
        }

        $this->updateBookingProductDates($booking);
        return $products;
    }

    /**
     * [updateBookingProducts description]
     *
     * @param   Booking  $booking                    [$booking description]
     * @param   [type]   $bookingProductsAttributes  [$bookingProductsAttributes description]
     *
     * @return  [type]                               [return description]
     */
    public function updateBookingProducts(Booking $booking, $bookingProductsAttributes)
    {
        $products = collect();

        $productDates = null;
        foreach ($bookingProductsAttributes as $attributes) {
            
            $bookingProduct = app(BookingProductRepository::class)->find($attributes['id']);
            if($bookingProduct == null) continue;

            if(isset($attributes['price'])){
                $attributes['price'] = sanitizeMoney($attributes['price']);
            }
            if(isset($attributes['price_net'])){
                $attributes['price_net'] = sanitizeMoney($attributes['price_net']);
            } 
            if (isset($attributes['date'])) {
                $attributes['date'] = convertDate($attributes['date']);
            }
            unset($attributes['booking_id']);
            unset($attributes['id']);

            $bookingProduct = app(BookingProductRepository::class)->update($bookingProduct, $attributes);
            $products->push($bookingProduct);
            if(isset($attributes["date"])){
                // Rule 01032022: The first main product date will be the "starts_at" date at the booking.
                if($productDates == null){
                    $booking->starts_at     = $attributes["date"];
                    $booking->save();
                }
                $productDates[]     = $attributes["date"];
            }
        }

        $this->updateBookingProductDates($booking);
        

        return $products;
    }

    /**
     * [updateBookingProductDates description]
     *
     * @param   Booking  $booking                    [$booking description]
     * @param   [type]   $bookingProductsAttributes  [$bookingProductsAttributes description]
     *
     * @return  [type]                               [return description]
     */
    public function updateBookingProductDates(Booking $booking)
    {
        $productDates = null;

        foreach($booking->bookingProducts as $bookingProduct){
            $productDates[] = $bookingProduct->date->format("Y-m-d");
        }

        if($productDates != null){
            $booking->product_dates = array_unique($productDates);
            $booking->save();
        }
    }

    /**
     * [destroyBookingProducts description]
     *
     * @param   Booking  $booking           [$booking description]
     * @param   [array]   $bookingProduct    [$bookingProduct description]
     *
     * @return  [type]                               [return description]
     */
    public function destroyBookingProducts(Booking $booking, $bookingProducts)
    {

        if(is_array($bookingProducts)){
            foreach($bookingProducts as $bookingProduct){
                $this->destroyBookingProduct($booking, $bookingProduct);
            }
        }

        return true;
    }

    /**
     * [destroyBookingProduct description]
     *
     * @param   Booking  $booking           [$booking description]
     * @param   [type]   $bookingProduct    [$bookingProduct description]
     *
     * @return  [type]                               [return description]
     */
    public function destroyBookingProduct(Booking $booking, $bookingProduct)
    {

        app(BookingProductRepository::class)->putStock($bookingProduct, $booking->passengers);

        app(BookingProductRepository::class)->delete($bookingProduct);

        return true;
    }
    
    /**
     * [getInclusions description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function getInclusions(Booking $booking)
    {
        $inclusions = collect();

        foreach ($booking->bookingProducts as $bookingProduct) {
            $product = $bookingProduct->getProduct();

            if (!($product instanceof LongtripAccommodationsPricing)) {
                $productInclusions = $product->getInclusions();
                $inclusions->push($productInclusions);
            }
        }

        $inclusions = $inclusions->flatten(1)
            ->unique('id')
            ->values();

        return $inclusions;
    }

    /**
     * [getObservations description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function getObservations(Booking $booking)
    {
        $observations = collect();

        foreach ($booking->bookingProducts as $bookingProduct) {
            $product = $bookingProduct->getProduct();

            if (!($product instanceof LongtripAccommodationsPricing)) {
                $productObservations = $product->getObservations();
                $observations->push($productObservations);
            }
        }

        $observations = $observations->flatten(1)
            ->unique('id')
            ->values();

        return $observations;
    }

    /**
     * [processBookingPayment description]
     *
     * @param   Booking  $booking  [$booking description]
     * @param   PaymentMethod    $paymentMethod  [$paymentMethod description]
     *
     * @return  [type]             [return description]
     */
    public function processBookingPayment(Booking $booking, $paymentMethod, $paymentAttributes, $installments)
    {
        $pivotPaymentMethods        = $booking->package()->first()->paymentMethods()->withPivot(['processor', 'max_installments'])
            ->where('payment_method_id', $paymentAttributes['payment_method_id'])
            ->first();
        $bookingBillRepository = app(BookingBillRepository::class);
        $bookingBillRepository->setBookingRepository($this);

        if ($paymentMethod->isCredit()) {
            switch($pivotPaymentMethods->pivot->processor){
                case Processor::OFFLINE:
                    $bookingBillRepository->payBookingCreditCardOffline($booking, $paymentAttributes);
                    break;
                case Processor::CIELO:
                    try{
                        $bookingBillRepository->payBookingCreditCardInstallments($booking, $paymentAttributes);
                        $paymentAttributes['number'] = substr($paymentAttributes['number'], 0, 4);
                        unset($paymentAttributes['cvv']);
                        $this->setSuccessMessages(
                            $bookingBillRepository->getSuccessMessages()
                        );
                    }catch(PaymentErrorException $ex){
                        $this->addErrorMessage(
                            __("frontend.misc.cartao_nao_aprovado", ['message' => $ex->getMessage(), 'link' => route(getRouteByLanguage('frontend.my-account.bookings.show'), $booking->id)])
                        );
                        $this->setError();  
                    }catch(Exception $ex){
                        $this->addErrorMessage(
                            $ex->getMessage()
                        );
                        $this->setError();  
                    }
                    break;
                case Processor::PAYPAL:
                    // If this is not set, its because the booking is already created.
                    if($this->postponePayment != BookingPayments::POSTPONE_PAYMENT){
                        $bookingBillRepository->payBookingPaypalInstallments($booking, $paymentAttributes);
                    }else{
                        $this->postponePayment = BookingPayments::POSTPONE_WAITING;
                    }
                    break;
                default:

            }
            
            /**
             * If everything is ok, then we save the operation data.
             */
            ClientPaymentDataset::create([
                'booking_id' => $booking->id,
                'client_id' => $booking->bookingClient->client_id,
                'processor' => end($installments)['processor'],
                'payload' => json_encode($paymentAttributes),
            ]);
            event(new BookingStatusEvent($booking));

        }
    }

    /**
     * [processBookingFailedPayment description]
     *
     * @param   Booking  $booking  [$booking description]
     * @param   PaymentMethod    $paymentMethod  [$paymentMethod description]
     *
     * @return  [type]             [return description]
     */
    public function processBookingFailedPayment(Booking $booking, BookingBill $bookingBill)
    {
        $pivotPaymentMethods        = $booking->package()->first()->paymentMethods()->withPivot(['processor', 'max_installments'])
            ->where('payment_method_id', $bookingBill->payment_method_id)
            ->first();
        $bookingBillRepository = app(BookingBillRepository::class);
        $bookingBillRepository->setBookingRepository($this);

        if ($bookingBill->paymentMethod->isCredit()) {
            switch($pivotPaymentMethods->pivot->processor){
                case Processor::PAYPAL:
                    $amount_transaction = $bookingBill->total;
                    $payload    = "";
                    $service    = app(Paypal::class);
                    $transaction = $service->failTransaction($booking, $bookingBill, $payload);
                    break;
                default:
                break;
            }
        }
    }

    /**
     * [processBookingApprovedPayment description]
     *
     * @param   Booking  $booking  [$booking description]
     * @param   PaymentMethod    $paymentMethod  [$paymentMethod description]
     *
     * @return  [type]             [return description]
     */
    public function processBookingApprovedPayment(Booking $booking, BookingBill $bookingBill, $_data=null)
    {
        $pivotPaymentMethods        = $booking->package()->first()->paymentMethods()->withPivot(['processor', 'max_installments'])
            ->where('payment_method_id', $bookingBill->payment_method_id)
            ->first();
        $bookingBillRepository = app(BookingBillRepository::class);
        $bookingBillRepository->setBookingRepository($this);

        if ($bookingBill->paymentMethod->isCredit()) {
            switch($pivotPaymentMethods->pivot->processor){
                case Processor::PAYPAL:
                    $amount_transaction = $bookingBill->total;
                    $service    = app(Paypal::class);
                    $transaction = $service->approve($booking, $bookingBill, $_data);
			        event(new BookingStatusEvent($booking));
                    break;
                default:
                break;
            }
        }
    }

    /**
     * [processEmailInformaton description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function processEmailInformaton(Booking $booking)
    {
        event(new BookingCreatedEvent($booking, $this));
    }

}
