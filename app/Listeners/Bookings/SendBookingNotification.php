<?php

namespace App\Listeners\Bookings;

use App\Events\Bookings\BookingCreatedEvent;
use App\Events\CreditCardOfflineEvent;
use App\Mail\Bookings\BookingBackofficeCreatedMail;
use App\Mail\Bookings\BookingCreatedMail;
use App\Mail\Bookings\BookingProviderCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendBookingNotification implements ShouldQueue
{

    private $booking            = null;

    private $bookingRepository  = null;


    /**
     * Handle the event.
     *
     * @param  BookingCreatedEvent  $event
     * @return void
     */
    public function handle(BookingCreatedEvent $event)
    {
        $this->booking              = $event->booking;
        $this->bookingRepository    = $event->bookingRepository;

        $this->sendBookingNotification();
        $this->sendBookingOfficeNotification();
        $this->sendBookingProviderNotification();
    }

    /**
     * [sendBookingNotification description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]               [return description]
     */
    protected function sendBookingNotification()
    {
        $email  = $this->booking->bookingClient->email;
        $return =  Mail::to($email)
        ->send(new BookingCreatedMail($this->booking, $this->bookingRepository));
    }

    /**
     * [sendBookingOfficeNotification description]
     *
     * @param   Provider  $provider  [$provider description]
     *
     * @return  [type]               [return description]
     */
    protected function sendBookingOfficeNotification()
    {
        $email  = emailBackoffice();
        if($email !=""){
            $return =  Mail::to($email)
            ->send(new BookingBackofficeCreatedMail($this->booking, $this->bookingRepository));
        }
    }

    /**
     * [sendBookingProviderNotification description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]               [return description]
     */
    protected function sendBookingProviderNotification()
    {
        $email  = $this->booking->offer->provider->email;
        $return =  Mail::to($email)
        ->send(new BookingProviderCreatedMail($this->booking, $this->bookingRepository));
    }

    /**
     * Handle a job failure.
     *
     * @param  ClientCreatedEvent $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(BookingCreatedEvent $event, $exception)
    {
        bugtracker()->notifyException($exception);
    }
}
