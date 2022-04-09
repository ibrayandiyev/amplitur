<?php

namespace App\Listeners\Bookings;

use App\Enums\ProcessStatus;
use App\Events\Bookings\BookingCreatedEvent;
use App\Events\Bookings\BookingStatusEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingProcessStatusChange implements ShouldQueue
{

    private $booking            = null;

    private $bookingRepository  = null;


    /**
     * Handle the event.
     *
     * @param  BookingCreatedEvent  $event
     * @return void
     */
    public function handle(BookingStatusEvent $event)
    {
        $this->booking              = $event->booking;
        $this->processStatus();
    }

    /**
     * [sendBookingOfficeNotification description]
     *
     * @param   Provider  $provider  [$provider description]
     *
     * @return  [type]               [return description]
     */
    protected function processStatus()
    {
        $booking        = $this->booking;
        $totalBill      = count($booking->bookingBills);
        $totalBillPaid  = count($booking->bookingBills->where("status", ProcessStatus::PAID));

        if($totalBill == $totalBillPaid && $booking->status == ProcessStatus::CONFIRMED){
            $booking->setPaymentStatus(ProcessStatus::CONFIRMED);
        }
        if($totalBillPaid < $totalBill){
            $booking->setPaymentStatus(ProcessStatus::ON_GOING);
        }
        $booking->save();
        $booking->refresh();
    }


    /**
     * Handle a job failure.
     *
     * @param  ClientCreatedEvent $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(BookingStatusEvent $event, $exception)
    {
        bugtracker()->notifyException($exception);
    }
}
