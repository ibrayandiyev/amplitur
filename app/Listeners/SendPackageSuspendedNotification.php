<?php

namespace App\Listeners;

use App\Enums\ProcessStatus;
use App\Events\PackageUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPackageSuspendedNotification implements ShouldQueue
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
     * @param  PackageUpdatedEvent $event
     * @return bool
     */
    public function shouldQueue(PackageUpdatedEvent $event)
    {
        return $event->provider->status == ProcessStatus::SUSPENDED;
    }

    /**
     * Handle a job failure.
     *
     * @param  PackageUpdatedEvent $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(PackageUpdatedEvent $event, $exception)
    {
        bugtracker()->notifyException($exception);
    }
}
