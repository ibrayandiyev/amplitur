<?php

namespace App\Mail\Bookings;

use App\Enums\Bookings\BookingNotifications;
use App\Models\Booking;
use App\Models\Provider;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, Provider $provider=null, $type=null)
    {
        $this->booking  = $booking;
        $this->provider = $provider;
        $this->type     = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $client     = $this->booking->client;
        $package    = $this->booking->package;
        switch($this->type){
            case BookingNotifications::NOTIFICATION_CLIENT:
                $template   = "email.bookings.booking_notifications_client";
                $subject    = __('mail.client.comun.update') . " - " . __('mail.client.comun.booking_code') . " - " . $this->booking->id;
                break;
            case BookingNotifications::NOTIFICATION_PROVIDER:
            default:
                $template   = "email.bookings.booking_notifications_provider";
                $subject    = __('mail.provider.comun.update') . " - " . __('mail.provider.comun.booking_code') . " - " . $this->booking->id;
                break;
        }
        $building = $this
            ->subject($subject)
            ->html(view(
            $template,
            [
                'booking'   => $this->booking,
                'provider'  => $this->provider,
                'package'   => $package,
                'client'    => $client,
            ]));
        return $building;
    }
}
