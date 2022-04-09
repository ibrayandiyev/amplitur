<?php

namespace App\Policies;

use App\Models\Booking;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function manage($provider, Booking $booking)
    {
        if(user()->canManageProviders()){ return true;}     // is master.
        $provider_id    = user()->id;
        if(!isset($booking->offer)){ return false;}
        return $booking->offer->provider_id == $provider_id;
    }

    public function view($user=null, $booking=null)
    {
        if($user->id != $booking->client_id){
            return false;
        }
        return true;
    }
}
