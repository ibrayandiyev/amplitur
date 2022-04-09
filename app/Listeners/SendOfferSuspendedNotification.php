<?php

namespace App\Listeners;

use App\Enums\ProcessStatus;
use App\Events\OfferUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOfferSuspendedNotification implements ShouldQueue
{
    public $queue = 'notifications';

    /**
     * Handle the event.
     *
     * @param  OfferUpdatedEvent  $event
     * @return void
     */
    public function handle(OfferUpdatedEvent $event)
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
        return $event->provider->status == ProcessStatus::SUSPENDED;
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
