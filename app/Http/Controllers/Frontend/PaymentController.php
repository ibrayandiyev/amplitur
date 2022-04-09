<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\FormPaymentRequest;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Repositories\BookingRepository;
use App\Repositories\ClientRepository;
use App\Repositories\PaymentMethodRepository;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * @var BookingRepository
     */
    protected $bookingRepository;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    public function __construct(
        BookingRepository $bookingRepository,
        PaymentMethodRepository $paymentMethodRepository,
        ClientRepository $clientRepository
    )
    {
        $this->bookingRepository            = $bookingRepository;
        $this->paymentMethodRepository      = $paymentMethodRepository;
        $this->clientRepository             = $clientRepository;

        $this->middleware('auth:clients');
    }

    /**
     * [creditCardPaymentForm description]
     *
     * @param   Booking  $booking  [$booking description]
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]             [return description]
     */
    public function creditCardPaymentForm(Booking $booking, BookingBill $bookingBill)
    {
        if(!$this->checkAllow("view", [Booking::class, $booking])){
            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.active'))->withErrors(__('resources.bookings.invoice_not_found'));
        }

        try {
            return view('frontend.my-account.payment.credit_card_parcel_payment')
                ->with('booking'    , $booking)
                ->with('bookingBill', $bookingBill)
                ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [doPayment description]
     *
     * @param   Booking  $booking  [$booking description]
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]             [return description]
     */
    public function doPayment(FormPaymentRequest $request, Booking $booking, BookingBill $bookingBill)
    {
        if(!$this->checkAllow("view", [Booking::class, $booking])){
            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.active'))->withErrors(__('resources.bookings.invoice_not_found'));
        }

        try {
            $paymentAttributes  = $request->except(["_token"]);
            $paymentMethod      = app(PaymentMethodRepository::class)->find($bookingBill->payment_method_id);

            $payment            = sanitizeCreditCardFields($paymentAttributes);
            $installments       = [];
            if($payment != null && is_array($payment)){
                $paymentAttributes = array_merge($paymentAttributes, $payment);
                $paymentMethodInstallments  = $paymentMethod->getBookingInstallments($booking);
                $installments               = $paymentMethodInstallments[$paymentAttributes['installments']];
            }

            $paymentAttributes['installment']       = $bookingBill->installment;
            $paymentAttributes['payment_method_id'] = $bookingBill->payment_method_id;
            $this->bookingRepository->processBookingPayment($booking, $paymentMethod, $paymentAttributes, $installments);
            if($this->bookingRepository->hasErrors()){
                return back()->withError($this->bookingRepository->getErrorMessages()[0]);
            }
            return redirect()
                ->route(getRouteByLanguage('frontend.my-account.bookings.show'), ['booking' => $booking])
                ->withSuccess(__('resources.bookings.payment_done') . $this->bookingRepository->firstSuccessMessage());
            
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [failedPayment description]
     *
     * @param   Booking  $booking  [$booking description]
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]             [return description]
     */
    public function failedPayment(Booking $booking, BookingBill $bookingBill)
    {
        if(!$this->checkAllow("view", [Booking::class, $booking])){
            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.active'))->withErrors(__('resources.bookings.invoice_not_found'));
        }

        try {
            $this->bookingRepository->processBookingFailedPayment($booking, $bookingBill);
            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.show'), ['booking' => $booking])->withError(__('resources.bookings.failed_payment'));

        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

        /**
     * [approvedPayment description]
     *
     * @param   Booking  $booking  [$booking description]
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]             [return description]
     */
    public function approvedPayment(Request $request, Booking $booking, BookingBill $bookingBill)
    {
        if(!$this->checkAllow("view", [Booking::class, $booking])){
            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.active'))->withErrors(__('resources.bookings.invoice_not_found'));
        }

        try {
            $_data  = $request->except(['_token']);
            $this->bookingRepository->processBookingApprovedPayment($booking, $bookingBill, $_data);
            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.show'), ['booking' => $booking])->withSuccess(__('resources.bookings.payment_complete'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }
}
