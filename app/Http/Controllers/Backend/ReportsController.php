<?php

namespace App\Http\Controllers\backend;

use App\Enums\Bookings\BookingLogsOperation;
use App\Http\Controllers\Controller;
use App\Models\BookingBill;
use App\Models\Provider;
use App\Repositories\BookingBillRepository;
use App\Repositories\BookingLogRepository;
use App\Repositories\BookingRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\NewsletterRepository;
use App\Repositories\OfferRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\ProviderRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    const REPORT_NEWSLETTER_PER_PAGE    = 100;
    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * @var BookingLogRepository
     */
    protected $bookingLogRepository;

    /**
     * @var NewsletterRepository
     */
    protected $newsletterRepository;

    /**
     * @var OfferRepository
     */
    protected $offerRepository;

    public function __construct(
        BookingRepository $bookingRepository,
        BookingBillRepository $bookingBillRepository,
        BookingLogRepository $bookingLogRepository,
        CompanyRepository $companyRepository,
        NewsletterRepository $newsletterRepository,
        OfferRepository $offerRepository,
        PackageRepository $packageRepository,
        ProviderRepository $providerRepository,
        PaymentMethodRepository $paymentMethodRepository,
        Request $req
        )
    {
        $this->bookingRepository        = $bookingRepository;
        $this->bookingBillRepository    = $bookingBillRepository;
        $this->bookingLogRepository     = $bookingLogRepository;
        $this->companyRepository        = $companyRepository;
        $this->newsletterRepository     = $newsletterRepository;
        $this->offerRepository          = $offerRepository;
        $this->packageRepository        = $packageRepository;
        $this->paymentMethodRepository  = $paymentMethodRepository;
        $this->providerRepository       = $providerRepository;
        $this->per_page                 = 100;
    }


    public function report_accountant(Request $request){
        if (!user()->canSeeMasterReport()) {
            forbidden();
        }
        
        $_filter_params     = $request->except("_token");

        $selectedPackage    = null;
        $providers          = [];
        if(isset($_filter_params["package_id"])){
            $selectedPackage    = $this->packageRepository->find($_filter_params["package_id"]);
            $_providers         = $selectedPackage->offers()->pluck("provider_id")->toArray();
            $providers          = app(Provider::class)->whereIn("id", $_providers)->get();
        }else{
            $_filter_params["package_id"]   = -1;
        }
        if(isset($_filter_params["booking_id"])){
            $_filter_params["id"]   = $_filter_params["booking_id"];
        }
        $bookings           = $this->bookingRepository->filter($_filter_params);
        $packages           = $this->packageRepository->list();
        
        return view('backend.reports.report_accountant.index')
                ->with('_filter_params', $_filter_params)
                ->with('selectedPackage', $selectedPackage)
                ->with('bookings', $bookings)
                ->with('packages', $packages)
                ->with('providers', $providers)
        ;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report_bookings(Request $request)
    {
        if (!user()->canSeeBookingReport()) {
            forbidden();
        }

        try {
            $_filter_params     = $request->except("_token");
            $bookings           = $this->bookingRepository->filter($_filter_params);

            return view('backend.reports.bookings.index')
                ->with('_filter_params', $_filter_params)
                ->with('bookings', $bookings)
                ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    public function report_newsletter(Request $request){
        if (!user()->canSeeBookingReport()) {
            forbidden();
        }

        $newsletters           = $this->newsletterRepository->list(ReportsController::REPORT_NEWSLETTER_PER_PAGE);

        return view('backend.reports.report_newsletter.index')
        ->with('newsletters', $newsletters);
    }

    public function report_event(Request $request){
        
        $provider           = auth('providers')->user();

        $_filter_params     = $request->except("_token");

        $selectedPackage    = null;
        $providers          = [];
        $companies          = [];
        if(isset($_filter_params["package_id"])){
            $selectedPackage    = $this->packageRepository->setActor(user())->find($_filter_params["package_id"]);
            $_providers         = $selectedPackage->offers()->pluck("provider_id")->toArray();
            $providers          = app(Provider::class)->whereIn("id", $_providers)->get();
        }else{
            $_filter_params["package_id"]   = -1;
        }
        if(isset($_filter_params["booking_id"])){
            $_filter_params["id"]   = $_filter_params["booking_id"];
        }
        $bookings           = $this->bookingRepository->filter($_filter_params);
        $packages           = $this->packageRepository->setActor(user())->list();
        if(!user()->isMaster()){
            $companies          = $this->companyRepository->setActor(user())->list();
        }
        
        return view('backend.reports.report_event.index')
                ->with('_filter_params', $_filter_params)
                ->with('selectedPackage', $selectedPackage)
                ->with('bookings', $bookings)
                ->with('packages', $packages)
                ->with('providers', $providers)
                ->with('companies', $companies)
        ;
    }

    public function report_stock(Request $request){
        $provider           = auth('providers')->user();
        
        $_filter_params     = $request->except("_token");

        $selectedPackage    = null;
        $providers          = [];
        $companies          = [];
        if(isset($_filter_params["package_id"])){
            $selectedPackage    = $this->packageRepository->setActor(user())->find($_filter_params["package_id"]);
            $_providers         = $selectedPackage->offers()->pluck("provider_id")->toArray();
            $providers          = app(Provider::class)->whereIn("id", $_providers)->get();
        }else{
            $_filter_params["package_id"]   = -1;
        }
        $offers             = $this->offerRepository->filter($_filter_params);
        $packages           = $this->packageRepository->setActor(user())->list();
        if(!user()->isMaster()){
            $companies          = $this->companyRepository->setActor(user())->list();
        }

        return view('backend.reports.report_stock.index')
            ->with('_filter_params', $_filter_params)
            ->with('selectedPackage', $selectedPackage)
            ->with('offers', $offers)
            ->with('packages', $packages)
            ->with('providers', $providers)
            ->with('companies', $companies)
            ;
    }

    public function report_detail_booking(Request $request){
        $provider           = auth('providers')->user();

        $_filter_params     = $request->except("_token");

        $selectedPackage    = null;
        $providers          = [];
        $companies          = [];
        if(isset($_filter_params["package_id"])){
            $selectedPackage    = $this->packageRepository->setActor(user())->find($_filter_params["package_id"]);
            $_providers         = $selectedPackage->offers()->pluck("provider_id")->toArray();
            $providers          = app(Provider::class)->whereIn("id", $_providers)->get();
        }else{
            $_filter_params["package_id"]   = -1;
        }
        if(isset($_filter_params["booking_id"])){
            $_filter_params["id"]   = $_filter_params["booking_id"];
        }
        $bookings           = $this->bookingRepository->filter($_filter_params);
        $packages           = $this->packageRepository->setActor(user())->list();
        if(!user()->isMaster()){
            $companies          = $this->companyRepository->setActor(user())->list();
        }
        return view('backend.reports.report_detail_booking.index')
            ->with('_filter_params', $_filter_params)
            ->with('selectedPackage', $selectedPackage)
            ->with('bookings', $bookings)
            ->with('packages', $packages)
            ->with('providers', $providers)
            ->with('companies', $companies)
            ;
    }

    public function report_email(Request $request){
        if (!user()->canSeeBookingReport()) {
            forbidden();
        }
        $_filter_params     = $request->except("_token");

        $packages           = $this->packageRepository->setActor(user())->list();
        $bookings           = $this->bookingRepository->filter($_filter_params);
        $selectedPackage    = null;
        if(isset($_filter_params["package_id"])){
            $selectedPackage    = $this->packageRepository->setActor(user())->find($_filter_params["package_id"]);
        }
        return view('backend.reports.report_email.index')
        ->with('_filter_params', $_filter_params)
        ->with('bookings', $bookings)
        ->with('selectedPackage', $selectedPackage)
        ->with('packages', $packages)
        ;
    }

    public function report_bills(Request $request){
        if (!user()->canSeeBookingReport()) {
            forbidden();
        }
        try {
            $_filter_params     = $request->except("_token");
            $provider           = auth('providers')->user();
            
            $paymentMethods             = $this->paymentMethodRepository->list();
            if(!isset($_filter_params["start_date"])){
                $_filter_params["start_date"]   = Carbon::now()->subDays(10)->format("d/m/Y");
            }
            if(!isset($_filter_params["end_date"])){
                $_filter_params["end_date"]   = Carbon::now()->format("d/m/Y");
            }
            
            $bookingBills       = $this->bookingBillRepository->filter($_filter_params);
            return view('backend.reports.report_bills.index', 
                ['paymentMethods' => $paymentMethods,
                '_filter_params'  => $_filter_params,
                'bookingBills'  => $bookingBills
                ]);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    public function report_refund(Request $request){
        if (!user()->canSeeBookingReport()) {
            forbidden();
        }
        try {
            $_filter_params     = $request->except("_token");
            
            if(!isset($_filter_params["start_date"])){
                $_filter_params["start_date"]   = Carbon::now()->subDays(30)->format("d/m/Y");
            }
            if(!isset($_filter_params["end_date"])){
                $_filter_params["end_date"]   = Carbon::now()->format("d/m/Y");
            }

            $_filter_params["operation"]   = BookingLogsOperation::BOOKING_LOG_OPERATION_BOOKING_CANCELLATION;
            
            $reports       = $this->bookingLogRepository->filter($_filter_params);
            return view('backend.reports.report_refund.index', 
                [
                '_filter_params'  => $_filter_params,
                'reports'  => $reports
                ]);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    public function report_payments(Request $request){
        if (!user()->canSeeBookingReport()) {
            forbidden();
        }
        try {
            $_filter_params     = $request->except("_token");
            
            if(!isset($_filter_params["start_date"])){
                $_filter_params["start_date"]   = Carbon::now()->subDays(30)->format("d/m/Y");
            }
            if(!isset($_filter_params["end_date"])){
                $_filter_params["end_date"]     = Carbon::now()->format("d/m/Y");
            }

            $reports       = $this->bookingBillRepository->filter($_filter_params);
            return view('backend.reports.report_payments.index', 
                [
                '_filter_params'  => $_filter_params,
                'reports'  => $reports
                ]);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }


    public function report_payment_providers(Request $request){

        try {
            $_filter_params     = $request->except("_token");
            $provider           = auth('providers')->user();
            
            $bookings           = $this->bookingRepository->filter($_filter_params);
            $packages           = $this->packageRepository->setActor(user())->list();
            $providers          = $this->providerRepository->setActor(user())->list();
            $companies          = $this->companyRepository->setActor(user())->list();

            return view('backend.reports.report_payment_providers.index')
                ->with('_filter_params', $_filter_params)
                ->with('bookings', $bookings)
                ->with('companies', $companies)
                ->with('packages', $packages)
                ->with('providers', $providers)
                ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }
};
