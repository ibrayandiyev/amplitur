<?php

namespace App\Mail\Bookings;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingBackofficeCreatedMail extends Mailable
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
        $this->subject              = __('mail.backoffice.booking.confirm_purchase') . " - " . $this->booking->id . " - " . $this->booking->getName();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_template = "email.backoffice.bookings.booking";

        $string  = (String) view($email_template,
            ['booking'           => $this->booking,
            'bookingRepository' => $this->bookingRepository,
            'email_data'        => $this->bookingRepository->getEmailData()]);
        $building = $this
                        ->subject($this->subject)
                        ->html($string);
        return $building;
    }
}
