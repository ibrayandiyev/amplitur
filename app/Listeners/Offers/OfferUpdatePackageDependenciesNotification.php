<?php

namespace App\Listeners\Offers;

use App\Events\OfferUpdateDependenciesEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class OfferUpdatePackageDependenciesNotification implements ShouldQueue
{
    public $queue = 'notifications';

    /**
     * Handle the event.
     *
     * @param  OfferUpdateDependenciesEvent  $event
     * @return void
     */
    public function handle(OfferUpdateDependenciesEvent $event)
    {
        $offer = $event->offer;
        // Update Additionals
        if($offer->additionals()->get()){
            foreach($offer->additionals()->get() as $additional){
                $additional->package_id = $offer->package_id;
                $additional->currency   = $offer->currency;
                $additional->save();
            }
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  ClientCreatedEvent $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(OfferUpdateDependenciesEvent $event, $exception)
    {
        bugtracker()->notifyException($exception);
    }
}
