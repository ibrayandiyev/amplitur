<?php

namespace App\Listeners\Providers;

use App\Enums\ProcessStatus;
use App\Events\Providers\ProviderUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProviderActiveNotification implements ShouldQueue
{
    public $queue = 'notifications';

    /**
     * Handle the event.
     *
     * @param  ProviderUpdatedEvent  $event
     * @return void
     */
    public function handle(ProviderUpdatedEvent $event)
    {
        //
    }

    /**
     * Determine whether the listener should be queued.
     *
     * @param  ProviderUpdatedEvent $event
     * @return bool
     */
    public function shouldQueue(ProviderUpdatedEvent $event)
    {
        return $event->provider->status == ProcessStatus::ACTIVE;
    }

    /**
     * Handle a job failure.
     *
     * @param  ProviderUpdatedEvent $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(ProviderUpdatedEvent $event, $exception)
    {
        bugtracker()->notifyException($exception);
    }
}
