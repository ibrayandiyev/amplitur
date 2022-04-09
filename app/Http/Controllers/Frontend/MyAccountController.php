<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PaymentMethod as EnumsPaymentMethod;
use App\Enums\PaymentMethodCategory;
use App\Enums\Processor;
use App\Enums\ProcessStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Clients\PasswordUpdateRequest;
use App\Http\Requests\Frontend\FormPaymentRequest;
use Libs\Itau\Itaucripto;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Models\Client;
use App\Models\PaymentMethod;
use App\Repositories\BookingRepository;
use App\Repositories\ClientRepository;
use App\Repositories\CountryRepository;
use App\Repositories\InvoiceInformationRepository;
use App\Repositories\PaymentMethodRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Libs\Itau\Itau;

class MyAccountController extends Controller
{
    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var BookingRepository
     */
    protected $bookingRepository;

    /**
     * @var InvoiceInformationRepository
     */
    protected $invoiceInformationRepository;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    public function __construct(
        CountryRepository $countryRepository,
        BookingRepository $bookingRepository,
        InvoiceInformationRepository $invoiceInformationRepository,
        PaymentMethodRepository $paymentMethodRepository,
        ClientRepository $clientRepository
    )
    {
        $this->countryRepository            = $countryRepository;
        $this->bookingRepository            = $bookingRepository;
        $this->invoiceInformationRepository = $invoiceInformationRepository;
        $this->paymentMethodRepository      = $paymentMethodRepository;
        $this->clientRepository             = $clientRepository;

        $this->middleware('auth:clients');
    }

    /**
     * [index description]
     *
     * @return  [type]  [return description]
     */
    public function index()
    {
        $client = auth('clients')->user();

        return view('frontend.my-account.index')
            ->with('client', $client);
    }

    /**
     * [show description]
     *
     * @return  [type]  [return description]
     */
    public function show()
    {
        return view('frontend.my-account.show')
            ->with('client', $this->getClient());
    }

    /**
     * [edit description]
     *
     * @return  [type]  [return description]
     */
    public function edit()
    {
        $countries = $this->countryRepository->list();

        return view('frontend.my-account.edit')
            ->with('countries', $countries)
            ->with('client', $this->getClient());
    }

    /**
     * [update description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function update(Request $request)
    {
        try {
            $attributes = $request->all();
            $client = $this->getClient();

            $this->clientRepository->update($client, $attributes);

            return redirect()->route(getRouteByLanguage('frontend.my-account.edit'))->withSuccess(__('frontend.misc.cadastro_atualizado'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [editPassword description]
     *
     * @return  [type]  [return description]
     */
    public function editPassword()
    {
        return view('frontend.my-account.edit-password');
    }

    /**
     * [updatePassword description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function updatePassword(PasswordUpdateRequest $request)
    {
        try {
            $attributes = $request->all();
            $client = $this->getClient();

            $password = isset($attributes['passwordactive'])?$attributes['passwordactive']:null;
            if(!Auth::guard('clients')->attempt(["email" => $client->email, "password" => $password])){
                throw new \Exception("Invalid current password.");
            };

            $this->clientRepository->update($client, $attributes);
            Auth::guard('clients')->attempt(["email" => $client->email, "password" => $password]);

            return redirect()->route(getRouteByLanguage('frontend.my-account.editPassword'))->withSuccess(__('frontend.misc.senha_alterada_sucesso'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [showActiveBookings description]
     *
     * @return  [type]  [return description]
     */
    public function showActiveBookings()
    {
        $bookings = $this->bookingRepository->getActiveBookings($this->getClient());

        return view('frontend.my-account.bookings.active')
            ->with('bookings', $bookings);
    }

    /**
     * [showActiveBookings description]
     *
     * @return  [type]  [return description]
     */
    public function showPastBookings()
    {
        $bookings           = $this->bookingRepository->getPastBookings($this->getClient());
        $bookingLegacies    = $this->bookingRepository->getBookingLegacies($this->getClient());

        return view('frontend.my-account.bookings.past')
            ->with('bookings', $bookings)
            ->with('bookingLegacies', $bookingLegacies)
            ;
    }

    /**
     * [showActiveBookings description]
     *
     * @return  [type]  [return description]
     */
    public function showBooking(Booking $booking)
    {
        if(!$this->checkManageBooking($booking)){
            return $this->redirectIndex($booking);
        }
        if($booking->getProduct() == null){
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'))->withError(__('messages.booking_with_product_problem'));

        }
        switch($booking->status){
            case ProcessStatus::CANCELED:
            case ProcessStatus::REFUNDED:
                return view('frontend.my-account.bookings.show_lean')
                    ->with('booking', $booking);
                break;
            default:
                break;

        }
        return view('frontend.my-account.bookings.show')
            ->with('booking', $booking);
    }

    /**
     * [showActiveBookings description]
     *
     * @return  [type]  [return description]
     */
    public function showBookingContract(Booking $booking)
    {
        try {

            $contract = view('frontend.booking.contract')
                ->with('mustPreRenderPhone', true)
                ->with('showPaymentDetails', true)
                ->with('booking', $booking)
                ->render();

            $contractFormatted = view('frontend.booking.contract')
                ->with('contract', $contract)
                ->with('booking', $booking)
                ->with('mustPreRenderPhone', true)
                ->with('showPaymentDetails', true)
                ->with('isPdf', true)
                ->render();

            $dompdf = new \Dompdf\Dompdf;
            $options = $dompdf->getOptions();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isFontSubsettingEnabled', true);
            $dompdf->setOptions($options);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->loadHTML($contractFormatted);

            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed' => TRUE
                ]
            ]);

            $dompdf->setHttpContext($context);
            $dompdf->render();

            $output = $dompdf->output();

            $headers = [
                'Content-Type' => 'application/pdf',
            ];

           return response()->stream(function() use ($output) {
               echo $output;
           }, 200, $headers);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [showActiveBookings description]
     *
     * @return  [type]  [return description]
     */
    public function showBookingInvoice(Request $request, Booking $booking, BookingBill $bookingBill)
    {
        if(!$this->checkAllow("view", [Invoice::class, $booking])){
            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.active'))->withErrors(__('resources.bookings.invoice_not_found'));
        }
        if($booking->id != $bookingBill->booking_id){
            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.active'))->withErrors(__('resources.bookings.invoice_not_found'));
        }

        try {
            $paymentMethod = $bookingBill->paymentMethod()->first();
            if($paymentMethod->code != EnumsPaymentMethod::PM_TYPE_INVOICE){
                return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.active'))->withErrors(__('resources.bookings.invoice_not_found'));
            }
            $invoiceInfo = $this->invoiceInformationRepository->findBy([['currency_id', '=', $booking->currency_id]]);

            return view(getViewByLanguage('frontend.booking.invoices.invoice', "_"))
            ->with('bookingBill', $bookingBill)
            ->with('booking', $booking)
            ->with('bookingClient', $booking->bookingClient)
            ->with('bookingPassengers', $booking->bookingPassengers)
            ->with('invoiceInformation', $invoiceInfo)
            ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [generateBilletBill description]
     *
     * @param   Request      $request      [$request description]
     * @param   Booking      $booking      [$booking description]
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]                     [return description]
     */
    public function generateBilletBill(Request $request, Booking $booking, BookingBill $bookingBill)
    {
        try {
            /** @var PaymentMethodRepository $repository */
            $repository = app(PaymentMethodRepository::class);
            $hash = $repository->getShoplineHash($booking, $bookingBill);

            return view('frontend.my-account.payment.shopline')
                ->with('hash', $hash);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [changePaymentMethod description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function changePaymentMethod(Booking $booking)
    {
        if (!$booking->canChangePaymentMethod()) {
            return back()->withErrors('Não pode alterar forma de pagamento desta reserva');
        }

        try {
            $categoryConditional = PaymentMethodCategory::PM_CATEGORY_NATIONAL;

            if($booking->isForeigner()){
                $categoryConditional = PaymentMethodCategory::PM_CATEGORY_INTERNATIONAL;
            }
            $paymentMethods = $this->paymentMethodRepository->list();
            if($paymentMethods){
                $paymentMethods = $paymentMethods->where('category', '=', $categoryConditional);
            }

            return view('frontend.my-account.payment.change')
                ->with('booking', $booking)
                ->with('paymentMethods', $paymentMethods);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [changePaymentMethod description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function doChangePaymentMethod(FormPaymentRequest $request, Booking $booking)
    {
        if (!$booking->canChangePaymentMethod()) {
            return back()->withError('Não pode alterar forma de pagamento desta reserva');
        }

        try {
            $attributes = $request->all();

            $payment['payment_method_id'] = $attributes['formapag'];
            $payment['installments'] = $attributes['parcelas'];
            $payment['number'] = $attributes['ct-numero'];
            $payment['holder'] = $attributes['ct-nome'];
            $payment['expirationDate'] = isset($attributes['ct-mes']) && isset($attributes['ct-ano']) ? str_pad('0', 2, $attributes['ct-mes']) . '/' . $attributes['ct-ano'] : null;
            $payment['cvv'] = $attributes['ct-cvc'];

            $this->bookingRepository->changePaymentMethod($booking, $payment);

            $bookingBill = $booking->bookingBills->where("processor", "!=", "offline")->sortBy("installment")->first();
            if($bookingBill){
                switch($bookingBill->processor){
                    case Processor::CIELO:
                        $paymentMethod      = app(PaymentMethodRepository::class)->find($bookingBill->payment_method_id);

                        $installments       = [];
                        if($payment != null && is_array($payment)){
                            $paymentAttributes = $payment;
                            $paymentMethodInstallments  = $paymentMethod->getBookingInstallments($booking);
                            $installments               = $paymentMethodInstallments[$paymentAttributes['installments']];
                        }
                        $paymentAttributes['installment']       = $bookingBill->installment;
                        $paymentAttributes['payment_method_id'] = $bookingBill->payment_method_id;
                        $this->bookingRepository->processBookingPayment($booking, $paymentMethod, $paymentAttributes, $installments);
                        if($this->bookingRepository->hasErrors()){
                            return back()->withError($this->bookingRepository->getErrorMessages()[0]);
                        }
                        break;
                }
            }

            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.show'), $booking)
                ->withSuccess(__('frontend.conta.ok_alteracao_pagamento'));
        } catch (Exception $ex) {
            dd($ex);
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [getClient description]
     *
     * @return  [type]  [return description]
     */
    protected function getClient(): ?Client
    {
        return auth('clients')->user();
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
        $user   = user();
        $result = $user->can('view', $booking);
        if(!$result){
            // Cannot see this booking
            return false;
        }
        return true;
    }

    private function redirectIndex(Booking $booking){
        return redirect()->route(getRouteByLanguage('frontend.my-account.index'))->withError(__('messages.no_access'));
    }
}
