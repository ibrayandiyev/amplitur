<?php

namespace App\Models\Traits;

use App\Enums\OfferType;

trait HasOfferTypeLabels
{
    /**
     * Get formatted type label
     *
     * @return string
     */
    public function getTypeLabelAttribute(): ?string
    {
        if ($this->type == OfferType::ALL) {
            return '<span class="label label-light-inverse">' . __('resources.offers.model.types.all') .'</span>';
        } else if ($this->type == OfferType::BUSTRIP) {
            return '<span class="label label-inverse">' . __('resources.offers.model.types.bus-trip') .'</span>';
        } else if ($this->type == OfferType::SHUTTLE) {
            return '<span class="label label-inverse">' . __('resources.offers.model.types.shuttle') .'</span>';
        } else if ($this->type == OfferType::HOTEL) {
            return '<span class="label label-inverse">' . __('resources.offers.model.types.hotel') .'</span>';
        } else if ($this->type == OfferType::LONGTRIP) {
            return '<span class="label label-inverse">' . __('resources.offers.model.types.longtrip') .'</span>';
        } else if ($this->type == OfferType::TICKET) {
            return '<span class="label label-light-inverse">' . __('resources.offers.model.types.ticket') .'</span>';
        } else if ($this->type == OfferType::FOOD) {
            return '<span class="label label-light-inverse">' . __('resources.offers.model.types.food') .'</span>';
        } else if ($this->type == OfferType::AIRFARE) {
            return '<span class="label label-light-inverse">' . __('resources.offers.model.types.airfare') .'</span>';
        } else if ($this->type == OfferType::TRAVEL_INSURANCE) {
            return '<span class="label label-light-inverse">' . __('resources.offers.model.types.travel-insurance') .'</span>';
        } else if ($this->type == OfferType::TRANSFER) {
            return '<span class="label label-light-inverse">' . __('resources.offers.model.types.transfer') .'</span>';
        } else if ($this->type == OfferType::ADDITIONAL) {
            return '<span class="label label-light-inverse">' . __('resources.offers.model.types.additional') .'</span>';
        } else {
            return '<span class="label">' . $this->type .'</span>';
        }
    }

    /**
     * Get formatted type label
     *
     * @return string
     */
    public function getTypeTextAttribute(): ?string
    {
        if ($this->type == OfferType::ALL) {
            return __('resources.offers.model.types.all');
        } else if ($this->type == OfferType::BUSTRIP) {
            return __('resources.offers.model.types.bus-trip');
        } else if ($this->type == OfferType::SHUTTLE) {
            return __('resources.offers.model.types.shuttle');
        } else if ($this->type == OfferType::HOTEL) {
            return __('resources.offers.model.types.hotel');
        } else if ($this->type == OfferType::LONGTRIP) {
            return __('resources.offers.model.types.longtrip');
        } else if ($this->type == OfferType::TICKET) {
            return __('resources.offers.model.types.ticket');
        } else if ($this->type == OfferType::FOOD) {
            return __('resources.offers.model.types.food');
        } else if ($this->type == OfferType::AIRFARE) {
            return __('resources.offers.model.types.airfare');
        } else if ($this->type == OfferType::TRAVEL_INSURANCE) {
            return __('resources.offers.model.types.travel-insurance');
        } else if ($this->type == OfferType::TRANSFER) {
            return __('resources.offers.model.types.transfer');
        } else if ($this->type == OfferType::ADDITIONAL) {
            return __('resources.offers.model.types.additional');
        } else {
            return $this->type;
        }
    }
}

