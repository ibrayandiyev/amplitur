<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PaymentMethodCategory;
use App\Exceptions\Bookings\NoCheckedConfirmationException;
use App\Exceptions\NoStockException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\BookingPaymentRequest;
use App\Http\Requests\Frontend\Bookings\PaymentRequest;
use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Repositories\CountryRepository;
use App\Repositories\PaymentMethodRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var BookingRepository
     */
    protected $bookingRepository;

    public function __construct(
        CountryRepository $countryRepository,
        BookingRepository $bookingRepository,
        PaymentMethodRepository $paymentMethodRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->bookingRepository = $bookingRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;

        $this->middleware('auth:clients');
    }
    /**
     * [summary description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function summary(Request $request)
    {
        try {
            $booking    = session()->get('booking');
            $promocode  = session()->get('promocode');

            $contract = view('frontend.booking.contract')
                ->with('mustPreRenderPhone', false)
                ->with('showPaymentDetails', false)
                ->with('booking', $booking)
                ->render();

            $countries = $this->countryRepository->list();

            return view('frontend.booking.summary')
                ->with('booking', $booking)
                ->with('promocode', $promocode)
                ->with('countries', $countries)
                ->with('contract', $contract)
                ->with('siteurlpolicy', route(getRouteByLanguage('frontend.pages.privacy_policy')))
                ->with('siteurlterms', route(getRouteByLanguage('frontend.pages.terms_use')));
        } catch (NoStockException $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route(getRouteByLanguage('frontend.booking.summary'))->withError(__('resources.bookings.no-stock'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route(getRouteByLanguage('frontend.packages.index'))->withError($ex->getMessage());
        }
    }

    /**
     * [payment description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function payment(PaymentRequest $request)
    {
        try {
            $attributes = $request->all();

            if(!isset($attributes['confirma']) || $attributes['confirma'] == null){
                throw new NoCheckedConfirmationException;
            }

            $booking    = session()->get('booking');
            $promocode  = session()->get('promocode');

            if($booking == null){
                return redirect()->route('frontend.booking.summary')->withError(__('resources.bookings.no-stock'));
            }
            $attributes['check_contract']   = Carbon::now()->format("Y-m-d H:i:s").", ". ip()  ;

            $booking = $this->bookingRepository->fillBooking($booking, $attributes);

            $categoryConditional = PaymentMethodCategory::PM_CATEGORY_NATIONAL;

            if($booking->isForeigner()){
                $categoryConditional = PaymentMethodCategory::PM_CATEGORY_INTERNATIONAL;
            }

            $paymentMethods = $booking->package->paymentMethods()->withPivot(['tax', 'discount', 'limiter', 'max_installments', 'processor'])->get();
            if($paymentMethods){
                $paymentMethods = $paymentMethods->where('category', '=', $categoryConditional);
            }

            session()->put('booking', $booking);

            return view('frontend.booking.payment')
                ->with('paymentMethods', $paymentMethods)
                ->with('booking', $booking)
                ->with('promocode', $promocode);
        } catch (NoStockException $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route(getRouteByLanguage('frontend.booking.summary'))->withError(__('resources.bookings.no-stock'));
        }catch (NoCheckedConfirmationException $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route(getRouteByLanguage('frontend.booking.summary'))->withError(__('resources.bookings.no-continue-check'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [store description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function store(BookingPaymentRequest $request)
    {
        try {
            $attributes = $request->all();

            $booking = session()->get('booking');

            $payment = sanitizeCreditCardFields($attributes);

            $booking = $this->bookingRepository->storeBooking($booking, $attributes, $payment);
            session()->put('booking', null);

            if($this->bookingRepository->hasErrors()){
                return redirect()->route(getRouteByLanguage('frontend.booking.finish'), ['booking' => $booking->id])
                ->withErrors($this->bookingRepository->getErrorMessages());
            }
            return redirect()->route(getRouteByLanguage('frontend.booking.finish'), ['booking' => $booking->id])
            ->withSuccess(__('frontend.misc.reserva_criada_success') . $this->bookingRepository->firstSuccessMessage())
            ;
        } catch (NoStockException $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('frontend.booking.summary')->withError(__('resources.bookings.no-stock'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [finsh description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function finish(Booking $booking, Request $request)
    {
        try {
            $attributes = $request->all();

            return view('frontend.booking.finish')
            ->with('siteurlcontato', route(getRouteByLanguage('frontend.pages.contact')))
            ->with('siteurlminhaconta', route(getRouteByLanguage('frontend.my-account.index')))
            ->with('booking', $booking);
        } catch (NoStockException $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('frontend.booking.summary')->withError(__('resources.bookings.no-stock'));
        } catch (Exception $ex) {
            dd($ex);
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }
}
