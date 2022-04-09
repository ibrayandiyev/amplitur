<?php

namespace App\Mail\Bookings;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingProviderCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, BookingRepository $bookingRepository)
    {
        $this->booking              = $booking;
        $this->bookingRepository    = $bookingRepository;
        $this->provider             = $booking->package->provider;
        $this->subject              = __('mail.provider.booking.provider_confirm_purchase')  . " - " . $this->booking->id . " - " . $this->booking->getName();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_template = "email.providers.bookings.booking";
        $string  = (String) view($email_template,
            ['booking'           => $this->booking,
            'bookingRepository' => $this->bookingRepository,
            'provider'          => $this->provider,
            'email_data'        => $this->bookingRepository->getEmailData()]);
        $building = $this
                        ->subject($this->subject)
                        ->html($string);
        return $building;
    }
}
