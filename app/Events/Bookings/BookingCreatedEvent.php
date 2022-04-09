<?php

namespace App\Events\Bookings;

use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Services\PaymentGateway\CreditCard;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Booking
     */
    public $booking;

    /**
     * @var BookingRepository
     */
    public $bookingRepository;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, BookingRepository $bookingRepository)
    {
        $this->booking              = $booking;
        $this->bookingRepository    = $bookingRepository;
    }
}
