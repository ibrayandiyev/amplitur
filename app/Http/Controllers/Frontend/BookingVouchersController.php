<?php

namespace App\Http\Controllers\Frontend;

use App\Exceptions\NoStockException;
use App\Http\Controllers\Controller;
use App\Models\BookingVoucher;
use App\Models\BookingVoucherFile;
use App\Repositories\BookingRepository;
use Exception;

class BookingVouchersController extends Controller
{
    /**
     * @var BookingRepository
     */
    protected $bookingRepository;

    public function __construct(
        BookingRepository $bookingRepository
    )
    {
        $this->bookingRepository = $bookingRepository;

        $this->middleware('auth:clients');
    }
    
    /**
     * [voucher description]
     *
     * @param   BookingVoucher  $bookingVoucher  [$bookingVoucher description]
     *
     * @return  [type]             [return description]
     */
    public function voucher(BookingVoucher $bookingVoucher)
    {
        $booking = $bookingVoucher->booking;
        if(!$this->checkAllow("view", [BookingVoucher::class, $booking])){
            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.active'))->withErrors(__('resources.bookings.voucher_not_found'));
        }
        try {

            $voucher = view(getViewByLanguage('frontend.my-account.bookings.vouchers.voucher', "_"))
                ->with('booking', $booking)
                ->with('voucher', $bookingVoucher)
                ->with('provider', $booking->offer->provider)
                ->render();

            return $voucher;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route(getRouteByLanguage('frontend.packages.index'))->withError($ex->getMessage());
        }
    }

    /**
     * [voucherFile description]
     *
     * @param   BookingVoucherFile  $bookingVoucher  [$bookingVoucher description]
     *
     * @return  [type]             [return description]
     */
    public function voucherFile(BookingVoucherFile $bookingVoucherFile)
    {
        $booking = $bookingVoucherFile->booking;
        if(!$this->checkAllow("view", [BookingVoucher::class, $booking])){
            return redirect()->route(getRouteByLanguage('frontend.my-account.bookings.active'))->withErrors(__('resources.bookings.voucher_not_found'));
        }
        try {

            $fileUrl    = $bookingVoucherFile->getVoucherUrl();
            $filename   = $bookingVoucherFile->title;
            $output     = file_get_contents($fileUrl);
            $headers    = [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => "attachment; filename='{$filename}'"
            ];

           return response()->stream(function() use ($output) {
               echo $output;
           }, 200, $headers);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route(getRouteByLanguage('frontend.packages.index'))->withError($ex->getMessage());
        }
    }

}
