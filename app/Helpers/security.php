<?php


if (!function_exists('checkChangeOfferAdditional')) {
    /**
     * Get remote ip address considering cloudflare
     *
     * @return  string
     */
    function checkChangeOfferAdditional($offer)
    {
        $canChange = !(user()->isProvider() && $offer->hasBookingAdditionals());
        return $canChange;
    }
}
