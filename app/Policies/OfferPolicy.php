<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
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

    public function manage($user)
    {
        return $user->canManageOffers();
    }

    public function update($user, Offer $offer)
    {
        return $user->canUpdateOffer($offer);
    }

    public function delete($user, Offer $offer)
    {
        return $user->canDeleteOffer($offer);
    }

    public function replicate($user, Offer $offer)
    {
        return $user->canReplicateOffer($offer);
    }

    public function manageImage($user, Offer $offer)
    {
        return $user->canMananageOfferImages($offer);
    }

    public function manageCurrency($user)
    {
        return $user->canManageOfferCurrency();
    }
}
