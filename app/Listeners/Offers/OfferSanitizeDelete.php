<?php

namespace App\Listeners\Offers;

use App\Events\OfferDestroyEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class OfferSanitizeDelete implements ShouldQueue
{
    public $queue = 'notifications';

    /**
     * Handle the event.
     *
     * @param  OfferUpdateDependenciesEvent  $event
     * @return void
     */
    public function handle(OfferDestroyEvent $event)
    {
        $offer = $event->offer;
        if ($offer->isBustrip() ){
            $bustripRoutes = $offer->bustripRoutes;
            foreach($bustripRoutes as $bustripRoute){
                $bustripBoardingLocations = $bustripRoute->bustripBoardingLocations;
                foreach($bustripBoardingLocations as $bustripBoardingLocation){
                    $bustripBoardingLocation->address->delete();
                }
            }
        }elseif($offer->isShuttle()){
            $shuttleRoutes = $offer->shuttleRoutes;
            foreach($shuttleRoutes as $shuttleRoute){
                $shuttleBoardingLocations = $shuttleRoute->shuttleBoardingLocations;
                foreach($shuttleBoardingLocations as $shuttleBoardingLocation){
                    $shuttleBoardingLocation->address->delete();
                }
            }
        }elseif($offer->isHotel()){
            $hotelOffer = $offer->hotelOffer;
            if($hotelOffer) { 
                if($hotelOffer->hotel){
                    $hotelOffer->hotel->delete(); 
                    if($hotelOffer->hotel->address != null){
                        $hotelOffer->hotel->address->delete(); 
                    }
                }
            }
        }elseif($offer->isLongtrip()){
            $longtripRoutes = $offer->longtripRoutes;
            foreach($longtripRoutes as $longtripRoute){
                $longtripBoardingLocations = $longtripRoute->longtripBoardingLocations;
                foreach($longtripBoardingLocations as $longtripBoardingLocation){
                    $longtripBoardingLocation->address->delete();
                }
            }
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  OfferDestroyEvent $event
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(OfferDestroyEvent $event, $exception)
    {
        bugtracker()->notifyException($exception);
    }
}
