<?php

namespace App\Listeners\Packages;

use App\Enums\ProcessStatus;
use App\Events\OfferUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class PackageBookingsNotification implements ShouldQueue
{
    public $queue = 'notifications';

    /**
     * Handle the event.
     *
     * @param  PackageUpdatedEvent  $event
     * @return void
     */
    public function handle(PackageUpdatedEvent $event)
    {
        //
    }

    /**
     * Determine whether the listener should be queued.
     *
     * @param  OfferUpdatedEvent $event
     * @return bool
     */
    public function shouldQueue(OfferUpdatedEvent $event)
    {
        return $event->provider->status == ProcessStatus::ACTIVE;
    }

    /**
     * Handle a job failure.
     *
     * @param  OfferUpdatedEvent $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(OfferUpdatedEvent $event, $exception)
    {
        bugtracker()->notifyException($exception);
    }
}
