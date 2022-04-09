<?php

namespace App\Models;

use App\Enums\OfferType;
use App\Models\Relationships\BelongsToLongtripAccommodationType;
use App\Models\Relationships\BelongsToLongtripRoute;
use App\Models\Relationships\HasManyLongtripAccommodationHotel;
use App\Models\Relationships\HasOneLongtripAccommodationHotel;
use App\Models\Traits\HasDateLabels;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LongtripAccommodation extends Model
{
    use BelongsToLongtripAccommodationType,
        BelongsToLongtripRoute,
        HasOneLongtripAccommodationHotel,
        HasManyLongtripAccommodationHotel,
        HasDateLabels,
        HasFactory;

    protected $fillable = [
        'offer_id',
        'longtrip_route_id',
        'longtrip_accommodation_type_id',
    ];


    /**
     * [getTypeLabelAttribute description]
     *
     * @return  string  [return description]
     */
    public function getTypeLabelAttribute(): ?string
    {
        return $this->type->name;
    }

    /**
     * [getTitle description]
     *
     * @return  string  [return description]
     */
    public function getTitle(): ?string
    {
        return $this->getTypeLabelAttribute();
    }

    /**
     * [getInclusions description]
     *
     * @return  [type]  [return description]
     */
    public function getInclusions()
    {
        return $this->longtripRoute->inclusions;
    }

    /**
     * [getAdditionals description]
     *
     * @return  [type]  [return description]
     */
    public function getAdditionals()
    {
        return $this->longtripRoute->additionals;
    }

    /**
     * [getObservations description]
     *
     * @return  [type]  [return description]
     */
    public function getObservations()
    {
        return $this->longtripRoute->observations;
    }

    /**
     * [getExtendedTitle description]
     *
     * @return  string  [return description]
     */
    public function getExtendedTitle(): ?string
    {
        return $this->name . $this->getTitle() . " @ {$this->offer->offer->company->company_name}";
    }

    /**
     * [getOffer description]
     *
     * @return  Offer   [return description]
     */
    public function getOffer(): Offer
    {
        return $this->offer;
    }

    /**
     * [getOfferType description]
     *
     * @return  string  [return description]
     */
    public function getOfferType(): ?string
    {
        return OfferType::LONGTRIP;
    }

    /**
     * [getCapacity description]
     *
     * @return  int     [return description]
     */
    public function getCapacity(): int
    {
        return $this->type->capacity;
    }

}
