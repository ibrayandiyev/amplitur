<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Repositories\BookingBillRepository;
use App\Repositories\BookingRepository;
use Exception;
use Illuminate\Http\Request;

class BookingBillController extends Controller
{
    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * @var BookingBillRepository
     */
    protected $bookingBillRepository;

    public function __construct(
        BookingRepository $bookingRepository,
        BookingBillRepository $bookingBillRepository,
        Request $req
        )
    {
        $this->repository                   = $bookingRepository;
        $this->bookingBillRepository        = $bookingBillRepository;
        parent::__construct($req);
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
            $this->repository->cancel($booking);

            return redirect()->route('backend.bookings.edit', $booking)->withSuccess(__('resources.bookings.canceled'));
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
    public function cancelBill(Request $request, BookingBill $bookingBill)
    {
        if (!user()->canCancelBookingBillPayment()) {
            forbidden();
        }
        $_data      = $request->all();
        $booking    = $bookingBill->booking;
        
        try {
            $this->bookingBillRepository->cancel(
                $bookingBill, $_data
            );
            return redirect()->route("backend.bookings.edit", $booking);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.edit', $booking)->withError($ex->getMessage());
        }
    }
}
